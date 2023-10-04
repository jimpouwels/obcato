<?php
defined('_ACCESS') or die;

interface Service {
    protected function getTextResource(string $identifier): string {
        return Session::getTextResource($identifier);
    }
}