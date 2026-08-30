<?php
declare(strict_types=1);
namespace Liberu\SocialNetwork\Discovery\Filament\Pages;
use Filament\Pages\Page;
use Liberu\SocialNetwork\Discovery\Actions\SearchDiscovery;
use Liberu\SocialNetwork\Profiles\Actions\GetProfile;
final class Search extends Page { protected string $view='social-network-discovery-filament::pages.search'; protected static string|\BackedEnum|null $navigationIcon='heroicon-o-magnifying-glass'; protected static string|\UnitEnum|null $navigationGroup='Social Network'; public string $query=''; public function refresh(): void {} public function results(GetProfile $get, SearchDiscovery $search): mixed { if (! auth()->check() || trim($this->query)==='') return collect(); return $search->handle($get->forUser(auth()->id()),$this->query); } }
