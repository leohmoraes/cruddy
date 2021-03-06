<?php

namespace Kalnoy\Cruddy\Schema\Fields;

use Illuminate\Database\Query\Builder;
use Kalnoy\Cruddy\Contracts\KeywordsFilter;
use Kalnoy\Cruddy\Helpers;

/**
 * Base text field class.
 *
 * This kind of fields don't have complex filters.
 *
 * @method $this placeholder(string $value)
 * @property string $placeholder
 *
 * @since 1.0.0
 */
abstract class BaseTextField extends BaseInput implements KeywordsFilter {

    /**
     * {@inheritdoc}
     */
    protected $class = 'Cruddy.Fields.Input';

    /**
     * The HTML <input> type attribute value.
     *
     * @var string
     */
    protected $inputType = 'text';

    /**
     * {@inheritdoc}
     */
    public function process($value)
    {
        $value = trim($value);

        return $value === '' ? null : $value;
    }

    /**
     * {@inheritdoc}
     */
    public function order(Builder $builder, $direction)
    {
        $builder->orderBy($this->id, $direction);

        return $this;
    }

    /**
     * @param Builder $builder
     * @param array $keywords
     *
     * @return void
     */
    public function applyKeywordsFilter(Builder $builder, array $keywords)
    {
        foreach ($keywords as $keyword)
        {
            $builder->orWhere($this->id, 'like', '%'.$keyword.'%');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return
        [
            'input_type' => $this->inputType,
            'placeholder' => Helpers::tryTranslate($this->get('placeholder')),

        ] + parent::toArray();
    }

}