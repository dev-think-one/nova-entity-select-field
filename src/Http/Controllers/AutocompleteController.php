<?php

namespace NovaEntitySelectField\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

class AutocompleteController extends Controller
{
    public function __invoke(NovaRequest $request)
    {
        $searchKeyword = $request->input('search');
        // TODO: currently limit we pass via requests as we have not field by attribute
        $limit             = $request->integer('limit') ?: 20;
        $currentEntityId   = $request->input('current');
        $entityResourceKey = $request->input('entityResourceKey');
        $withTrashed       = $request->boolean('withTrashed');

        throw_if(!$entityResourceKey, __('entityResourceKey is required.'));

        /** @var class-string<Resource>|Resource $resourceClass */
        $resourceClass = Nova::authorizedResources($request)
            ->filter(fn ($resource) => $resource::uriKey() == $entityResourceKey)
            ->first();
        throw_if(!$resourceClass, __("Resource for key [{$entityResourceKey}] not found"));

        /** @var Model $model */
        $model       = $resourceClass::newModel();
        $idColumn    = $model->getKeyName();
        $titleColumn = $resourceClass::$title;

        $builder = $resourceClass::buildIndexQuery($request, $model::query(), $searchKeyword, withTrashed: $withTrashed);

        $currentEntities = null;
        if ($currentEntityId) {
            $currentEntities = $model::query()->whereKey($currentEntityId)->get();
        }

        $builder->limit($limit);

        $items = $builder->get();

        if ($currentEntities?->isNotEmpty()) {
            foreach ($currentEntities as $currentEntity) {
                $items->prepend($currentEntity);
            }
            $items = $items->unique($idColumn);
        }


        return Response::json([
            'resources' =>
                $items->map(function (Model $row) use ($resourceClass, $idColumn, $titleColumn) {
                    $resource = new $resourceClass($row);
                    $avatar   = $resource->resolveAvatarField(app(NovaRequest::class));

                    if (!is_null($avatar)) {
                        $avatar = $avatar->resolveThumbnailUrl();
                    }

                    return [
                        'value'    => $row->$idColumn,
                        'display'  => $row->$titleColumn,
                        'avatar'   => $avatar ?: null,
                        'subtitle' => $resource->subtitle(),
                    ];
                })->all(),
            'withTrashed' => $withTrashed,
        ]);
    }
}
