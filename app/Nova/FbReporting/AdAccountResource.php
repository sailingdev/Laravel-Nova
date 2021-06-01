<?php

namespace App\Nova\FbReporting;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class AdAccountResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FbReporting\AdAccount::class;


    
    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Ad Accounts';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Ad Account';
    }


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'account_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'account_name', 'account_id', 'timezone', 'notes', 'environment'
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
            Text::make('Account Name')
            ->sortable()
            ->rules('required', 'max:255'),
            Text::make('Account ID')
            ->sortable()
            ->rules('required', 'max:255'),
            Select::make('Environment')->options([
                'rd' => 'Revenuedriver',
                'tt' => 'TechAds Media'
            ])->displayUsingLabels()
            ->rules('required'),
            Text::make('Timezone')
            ->sortable()
            ->rules('required', 'max:255'),
            Textarea::make('Configurations')
            ->sortable()
            ->rules('required')
            ->help(
                'Enter each configuration on a new line. For instance <br/>
                    duplicate_campaigns=true <br/>
                    fbreportmss_daily=true <br/>
                    fbreportmss_hourly=false <br/> <br/> 
                    <b>Available Configurations:</b> <br/>
                    duplicate_campaigns <br/>
                    fbreportmss_daily <br/>
                    fbreportmss_hourly <br/>
                    dailyreportmss_daily <br/>
                    update_bids_10 <br/>
                    update_bids_21 <br/>
                    adjust_budgets <br/>
                    fbengine <br/>
                    rdslackbot <br/>
                    site <br/>
                    dupno
                '
            ),
            Textarea::make('Notes')
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
        return [];
    }
}
