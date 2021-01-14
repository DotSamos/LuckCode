<?php

namespace luckCode\database\table\interfaces;

use luckCode\player\profile\interfaces\IProfile;

interface IProfileTable
{
    /**
     * @param string $player
     * @return array
     */
    public function getProfileData(string $player): array;

    /**
     * @param string $player
     * @return bool
     */
    public function hasProfile(string $player): bool;

    /**
     * @param string $player
     * @param bool $force
     * @return bool
     */
    public function registerNewProfile(string $player, bool $force = false): bool;

    /**
     * @param IProfile $profile
     * @return string
     */
    public function getExecutionForSave(IProfile $profile): string;

    /**
     * @param IProfile $profile
     * @return bool
     */
    public function saveProfile(IProfile $profile): bool;

    /**
     * @param IProfile[] $profiles
     * @return bool
     */
    public function saveAllProfiles(array $profiles): bool;
}