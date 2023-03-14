<?php

namespace Esoastor\JobQueueManager;

abstract class OneTimeJob extends Job
{
    protected bool $isConstant = false;
}