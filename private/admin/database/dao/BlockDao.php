<?php

interface BlockDao {
    public function getAllBlocks(): array;

    public function getBlocksByPosition(BlockPosition $position): array;

    public function getBlocksWithoutPosition(): array;

    public function getBlocksByPageAndPosition(Page $page, string $position_name): array;

    public function getBlocksByPage(Page $page): array;

    public function getBlockPositions(): array;

    public function getBlockPosition($position_id): ?BlockPosition;

    public function getBlock($id): ?Block;

    public function createBlock(): Block;

    public function updateBlock($block): void;

    public function deleteBlock($block): void;

    public function createBlockPosition(): BlockPosition;

    public function getBlockPositionByName($position_name): ?BlockPosition;

    public function updateBlockPosition($position): void;

    public function deleteBlockPosition($position): void;

    public function addBlockToPage($block_id, $page): void;

    public function deleteBlockFromPage($block_id, $page): void;
}