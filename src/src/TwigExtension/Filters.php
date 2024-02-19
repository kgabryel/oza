<?php

namespace App\TwigExtension;

use App\Config\Message\Error\ShoppingErrors;
use App\Config\Message\Error\ShoppingListErrors;
use App\Entity\ApiKey;
use ArrayAccess;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Filters extends AbstractExtension
{
    public function applicationKeys(Collection $keys, ?string $application = null): Collection
    {
        if ($application === null) {
            return $keys->filter(static fn(ApiKey $key): bool => $key->getApplication() === null);
        }

        return $keys->filter(static fn(ApiKey $key): bool => $key->getApplication()?->getName() === $application);
    }

    public function boolToInt(bool $value): int
    {
        return $value ? 1 : 0;
    }

    public function errorMessages(FormErrorIterator $errors): array
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getMessage();
        }

        return $messages;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('errorMessages', [
                $this,
                'errorMessages'
            ]),
            new TwigFilter('multiselectOptions', [
                $this,
                'multiselectOptions'
            ]),
            new TwigFilter('quickListPositions', [
                $this,
                'quickListPositions'
            ]),
            new TwigFilter('shoppingListPositions', [
                $this,
                'shoppingListPositions'
            ]),
            new TwigFilter('shoppingPositions', [
                $this,
                'shoppingPositions'
            ]),
            new TwigFilter('boolToInt', [
                $this,
                'boolToInt'
            ]),
            new TwigFilter('applicationKeys', [
                $this,
                'applicationKeys'
            ]),
            new TwigFilter('toString', [
                $this,
                'toString'
            ])
        ];
    }

    /**
     * @param ChoiceView[] $choices
     *
     * @return array
     */
    public function multiselectOptions(array $choices): array
    {
        $options = [];
        foreach ($choices as $choice) {
            $options[$choice->value] = $choice->label;
        }

        return $options;
    }

    public function quickListPositions(FormView $positions, bool $addEmptyPosition = false): array
    {
        $values = [];
        /** @var FormView $position */
        foreach ($positions as $position) {
            $element = [];
            foreach (
                [
                    'checked' => 'checked',
                    'position' => 'value'
                ] as $key => $valueKey
            ) {
                $element[$key] = self::mapPosition($position, $key, $valueKey);
            }
            $values[] = $element;
        }
        if (empty($values && $addEmptyPosition)) {
            $pairs = [
                'checked' => false,
                'position' => ''
            ];
            $position = [];
            foreach ($pairs as $key => $value) {
                $position[$key] = self::getEmptyValue($value);
            }
            $values[] = $position;
        }

        return $values;
    }

    private static function mapPosition(FormView $position, string $key, string $valueKey = 'value'): array
    {
        return [
            'value' => $position[$key]->vars[$valueKey],
            'errors' => self::parseErrors($position, $key)
        ];
    }

    private static function parseErrors(ArrayAccess $element, string $key): array
    {
        $errors = [];
        foreach ($element[$key]->vars['errors'] as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }

    private static function getEmptyValue(mixed $value): array
    {
        return [
            'value' => $value,
            'errors' => []
        ];
    }

    public function shoppingListPositions(FormView $positions, bool $addEmptyPosition = false): array
    {
        $errors = [];
        foreach ($positions->parent->vars['errors'] as $error) {
            $errors[(int)$error->getOrigin()->getName()] = $error->getMessage();
        }
        $values = [];
        /** @var FormView $position */
        foreach ($positions as $key => $position) {
            $unitErrors = self::parseErrors($position, 'unit');
            $productErrors = [];
            foreach ($position->vars['errors'] as $error) {
                $productErrors[] = $error->getMessage();
            }
            array_push($productErrors, ...self::parseErrors($position, 'position'));
            $productValue = sprintf('%s_%s', $position['type']->vars['value'], $position['position']->vars['value']);
            if (isset($errors[$key])) {
                if ($errors[$key] === ShoppingListErrors::UNIT_MISSING) {
                    $unitErrors[] = $errors[$key];
                } else {
                    $productErrors[] = $errors[$key];
                }
            }
            $values[] = [
                'checked' => self::mapPosition($position, 'checked', 'checked'),
                'shop' => self::mapPosition($position, 'shop'),
                'unit' => [
                    'value' => $position['unit']->vars['value'],
                    'errors' => $unitErrors
                ],
                'product' => [
                    'value' => $productValue,
                    'errors' => $productErrors
                ],
                'amount' => self::mapPosition($position, 'amount'),
                'description' => self::mapPosition($position, 'description')
            ];
        }
        if (empty($values && $addEmptyPosition)) {
            $pairs = [
                'shop' => null,
                'checked' => false,
                'unit' => '',
                'product' => '',
                'amount' => 1,
                'description' => ''
            ];
            $position = [];
            foreach ($pairs as $key => $value) {
                $position[$key] = self::getEmptyValue($value);
            }
            $values[] = $position;
        }

        return $values;
    }

    public function shoppingPositions(FormView $positions): array
    {
        $errors = [];
        foreach ($positions->parent->vars['errors'] as $error) {
            $errors[(int)$error->getOrigin()->getName()] = $error->getMessage();
        }
        $values = [];
        /** @var FormView $position */
        foreach ($positions as $key => $position) {
            $unitErrors = self::parseErrors($position, 'unit');
            $supplyErrors = self::parseErrors($position, 'supply');
            $productErrors = [];
            foreach ($position->vars['errors'] as $error) {
                $productErrors[] = $error->getMessage();
            }
            array_push($productErrors, ...self::parseErrors($position, 'position'));
            $productValue = sprintf('%s_%s', $position['type']->vars['value'], $position['position']->vars['value']);
            if (isset($errors[$key])) {
                if ($errors[$key] === ShoppingErrors::UNIT_MISSING) {
                    $unitErrors[] = $errors[$key];
                } else {
                    $productErrors[] = $errors[$key];
                }
            }
            $values[] = [
                'unit' => [
                    'value' => $position['unit']->vars['value'],
                    'errors' => $unitErrors
                ],
                'product' => [
                    'value' => $productValue,
                    'errors' => $productErrors
                ],
                'amount' => self::mapPosition($position, 'amount'),
                'price' => self::mapPosition($position, 'price'),
                'discount' => self::mapPosition($position, 'discount'),
                'supply' => [
                    'value' => $position['supply']->vars['value'],
                    'errors' => $supplyErrors
                ],
                'createSupply' => [
                    'value' => $position['createSupply']->vars['checked'],
                    'errors' => []
                ]
            ];
        }

        return $values;
    }

    public function toString(mixed $value): string
    {
        return (string)$value;
    }
}
