<?php namespace Kalnoy\Cruddy\Schema\Fields\Types;

use Illuminate\Database\Query\Builder;
use Kalnoy\Cruddy\Contracts\Filter;
use Kalnoy\Cruddy\Helpers;
use Kalnoy\Cruddy\Schema\Fields\BaseInput;

/**
 * The field for displaying select box.
 *
 * @property string $prompt
 * @method $this prompt(string $value)
 *
 * @since 1.0.0
 */
class Enum extends BaseInput implements Filter {

    /**
     * {@inheritdoc}
     */
    protected $class = 'Cruddy.Fields.Enum';

    /**
     * {@inheritdoc}
     */
    protected $type = 'enum';

    /**
     * {@inheritdoc}
     */
    protected $filterType = self::FILTER_COMPLEX;

    /**
     * @var array|Callable
     */
    public $items;

    /**
     * {@inheritdoc}
     */
    public function process($value)
    {
        $items = value($this->items);

        if ( ! isset($items[$value])) return null;

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilterConstraint(Builder $query, $data)
    {
        $query->where($this->id, $data);
    }

    /**
     * Translate items if possible.
     *
     * @param array $items
     *
     * @return array
     */
    protected function translateItems($items)
    {
        foreach ($items as $key => $value)
        {
            $items[$key] = Helpers::tryTranslate($value);
        }

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return
        [
            'prompt' => Helpers::tryTranslate($this->get('prompt')),
            'items' => $this->translateItems(value($this->items)),

        ] + parent::toArray();
    }
}