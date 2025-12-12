<?php

namespace App\Laravilt\Admin\Clusters;

use Laravilt\Panel\Cluster;

class Reports extends Cluster
{
    /**
     * The cluster's navigation icon.
     */
    protected static ?string $navigationIcon = 'BarChart3';

    /**
     * The cluster's navigation label.
     */
    protected static ?string $navigationLabel = 'Reports';

    /**
     * The cluster's navigation sort order.
     */
    protected static ?int $navigationSort = 10;

    /**
     * The cluster's navigation group.
     */
    protected static ?string $navigationGroup = null;

    /**
     * Whether the cluster should register navigation.
     */
    protected static bool $shouldRegisterNavigation = true;
}
