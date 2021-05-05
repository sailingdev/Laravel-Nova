<?php

namespace App\Nova\FbReporting;

use App\Nova\Actions\FbReporting\LoadFbPageRunningAdsCount;
use App\Nova\Actions\FbReporting\LoadIgAccountId;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class FbPageResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FbReporting\FbPage::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Fb Pages';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Fb Page';
    }


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'page_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'page_id', 'instagram_id', 'page_name', 'environment', 'is_published'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Page Name')
            ->sortable()
            ->rules('required', 'max:255'),
            Text::make('Page Id')
            ->sortable()
            ->rules('required', 'max:255'),
            Text::make('Instagram Id')
            ->sortable()
            ->rules('max:255'),
            Number::make('Running Ads')
            ->sortable()
            ->rules('max:255'),
            Select::make('Environment')->options([
                'rd' => 'Revenuedriver',
                'tt' => 'TechAds Media'
            ])->displayUsingLabels()
            ->rules('required'),
            Select::make('Is Published')->options([
                'true' => 'true',
                'false' => 'false'
            ])->sortable()
            ->rules('required')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new LoadIgAccountId,
            new LoadFbPageRunningAdsCount
        ];
    }
}
