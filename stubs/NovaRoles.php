<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use MakeIT\UserRoles\Role;

class NovaRoles extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = Role::class;

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = [
        'users'
    ];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'label',
        'name',
        'comment',
    ];

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group(): string
    {
        return __('Administrator');
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param NovaRequest $request
     * @param Builder     $query
     * @return Builder
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query;
    }

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return __('Roles');
    }

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return __('Role');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->hideFromIndex(),
            Text::make(__('Label'), 'label')->sortable()->rules('required', 'max:255'),
            Text::make(__('Name'), 'name')->onlyOnForms()->rules('required', 'max:255')->creationRules('unique:roles,name')->updateRules('unique:roles,name,{{resourceId}}'),
            Text::make(__('Comment'), 'comment')->nullable(),
            Boolean::make(__('Is Protected'), 'is_protected'),
            Text::make(__('Count Users'), function () {return $this->users->count() ? $this->users->count() : null;})->exceptOnForms(),
            BelongsToMany::make(__('Users'), 'users', NovaUsers::class)->searchable(),
        ];
    }
}
