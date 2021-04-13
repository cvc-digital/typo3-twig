<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2021 CARL von CHIARI GmbH
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Cvc\Typo3\CvcTwig\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService;

/**
 * @internal
 */
final class ImageExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('t3_uri_image', [static::class, 'imageUri']),
        ];
    }

    /**
     * Returns the URL to the given image. If an error occurs, then :code:`null` is returned and no error is raised.
     *
     * @param FileInterface|FileReference|null $image the image can be a reference or an instance of :code:`FileInterface` or :code:`FileReference`
     * @param string|null                      $crop  the JSON-formatted crop settings
     */
    public static function imageUri(
        string $src = null,
        bool $treatIdAsReference = false,
        $image = null,
        string $crop = null,
        string $cropVariant = 'default',
        string $width = '',
        string $height = '',
        int $minWidth = 0,
        int $minHeight = 0,
        int $maxWidth = 0,
        int $maxHeight = 0,
        bool $absolute = false): ?string
    {
        if ((is_null($src) && is_null($image)) || (!is_null($src) && !is_null($image))) {
            throw new \InvalidArgumentException('You must either specify a string src or a File object.', 1460976233);
        }

        try {
            $imageService = self::getImageService();
            $image = $imageService->getImage($src, $image, $treatIdAsReference);

            if ($crop === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $crop = $image->getProperty('crop');
            }

            $cropVariantCollection = CropVariantCollection::create((string) $crop);
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            $processingInstructions = [
                'width' => $width,
                'height' => $height,
                'minWidth' => $minWidth,
                'minHeight' => $minHeight,
                'maxWidth' => $maxWidth,
                'maxHeight' => $maxHeight,
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];

            $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);

            return $imageService->getImageUri($processedImage, $absolute);
        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
        }

        return '';
    }

    protected static function getImageService(): ImageService
    {
        return GeneralUtility::makeInstance(ObjectManager::class)->get(ImageService::class);
    }
}
