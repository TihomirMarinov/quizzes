<?php

namespace App\Contracts;


interface RuleAlias {
    /**
     * Alias rule to specific existing one (in Laravel)
     *
     * @return string
     */
    public function ruleAs(): string;
}
