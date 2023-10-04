<?php
defined('_ACCESS') or die;

interface PageService {
    function addSelectedBlocks(Page $page, array $selected_blocks): void;
}