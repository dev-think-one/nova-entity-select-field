<?php

namespace NovaEntitySelectField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Searchable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

class EntitySelect extends Field
{
    use Searchable;

    public $component = 'entity-select-field';

    /** @var class-string<Resource> */
    public string $entityResource;

    public int $limit = 10;

    public ?string $displayValue = null;

    public ?bool $viewable = null;

    /**
     * @param class-string<Resource> $entityResource
     * @param $name
     * @param $attribute
     * @param callable|null $resolveCallback
     */
    public function __construct(string $entityResource, $name = null, $attribute = null, callable $resolveCallback = null)
    {
        $this->entityResource = $entityResource;

        if (!$name) {
            $name = Nova::humanize(class_basename($entityResource));
        }

        parent::__construct($name, $attribute, $resolveCallback);
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function viewable($value = true)
    {
        $this->viewable = $value;

        return $this;
    }

    public function resolveForDisplay($resource, $attribute = null)
    {
        parent::resolveForDisplay($resource, $attribute);

        $titleColumn = $this->entityResource::$title;

        $currentEntity = $this->entityResource::newModel()::query()->whereKey($this->value)->first();

        if($currentEntity) {
            $this->displayValue = $currentEntity->$titleColumn;
        }
    }

    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);

        $viewable = !is_null($this->viewable) ? $this->viewable : $this->entityResource::authorizedToViewAny($request);

        return array_merge(parent::jsonSerialize(), [
            'entityResourceKey' => $this->entityResource::uriKey(),
            'softDeletes'       => $this->entityResource::softDeletes(),
            'withSubtitles'     => $this->withSubtitles,
            'debounce'          => $this->debounce,
            'limit'             => $this->limit,
            'viewable'          => $viewable,
            'display'           => $this->displayValue,
        ]);
    }
}
