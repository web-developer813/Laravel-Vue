<?php

# current mode
function current_mode($mode = null)
{
    if (session()->has('auth-nonprofit')) {
        $current_mode = 'nonprofit';
    } elseif (session()->has('auth-forprofit')) {
        $current_mode = 'forprofit';
    } else {
        $current_mode = 'volunteer';
    }

    return ($mode)
        ? $mode == $current_mode
        : $current_mode;
}

# current model
function currentModel($model = null)
{
    if (session()->has('auth-nonprofit')) {
        $model = Auth::user()->nonprofit;
    } elseif (session()->has('auth-forprofit')) {
        $model = Auth::user()->forprofit;
    } else {
        $model = Auth::user()->volunteer;
    }

    return $model;
}
