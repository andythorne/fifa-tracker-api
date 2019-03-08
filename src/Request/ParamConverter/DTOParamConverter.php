<?php

namespace App\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOParamConverter implements ParamConverterInterface
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $dtoClass = $configuration->getClass();
        if ($request->getMethod() === Request::METHOD_GET) {
            $payload = $request->query->all();

            if ($this->serializer instanceof DenormalizerInterface) {
                $dto = $this->serializer->denormalize($payload, $dtoClass, 'array');
            }
        } else {
            $payload = $request->getContent();

            $dto = $this->serializer->deserialize($payload, $dtoClass, 'json', [
                'allow_extra_attributes' => false,
            ]);
        }

        $errors = $this->validator->validate($dto);
        if (count($errors)) {
            throw new UnprocessableEntityHttpException();
        }

        $request->attributes->set($configuration->getName(), $dto);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        $implementations = class_implements($configuration->getClass());

        return isset($implementations[DTOInterface::class]);
    }
}
