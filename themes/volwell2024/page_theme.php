<?php
namespace Application\Theme\Volwell2024;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Area\Layout\Preset\Provider\ThemeProviderInterface;
use Concrete\Core\Page\Theme\Theme;

defined('C5_EXECUTE') or die(_("Access Denied."));

class PageTheme extends Theme implements ThemeProviderInterface
{
   protected $pThemeGridFrameworkHandle = 'bootstrap5';

   public function getThemeName()
   {
     return t('Volwell2024');
   }

   public function getThemeDescription()
   {
     return t('A Volunteer Wellington Theme using Bootstrap5');
   }

   public function getThemeAreaLayoutPresets()
   {
     return [];
   }

   public function registerAssets()
   {
     $c = \Page:: getCurrentPage();
     $currentPermissions = new \Permissions($c);
     $user = new \User;
     if ($currentPermissions->canViewToolbar() || $user->isLoggedIn())
     {
       $this->requireAsset('javascript', 'jquery');
       $this->requireAsset('javascript', 'jquery-ui');
       $this->requireAsset('javascript', 'bootstrap/*');
       $this->requireAsset('css', 'bootstrap/*');
    } else {
       $this->requireAsset('javascript', 'jquery');
       $this->requireAsset('javascript', 'jquery-ui');
       $this->providesAsset('javascript', 'bootstrap/*');
       $this->providesAsset('css', 'bootstrap/*');
     }

      $this->requireAsset('javascript', 'picturefill'); // Responsive images fallback
      $this->requireAsset('javascript', 'site');
      $this->requireAsset('css', 'font-awesome');


   }
}
?>
