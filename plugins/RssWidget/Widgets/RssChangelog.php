<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\RssWidget\Widgets;

use Piwik\Piwik;
use Piwik\Widget\WidgetConfig;
use Piwik\Plugins\RssWidget\RssRenderer;

class RssChangelog extends \Piwik\Widget\Widget
{
    public static function configure(WidgetConfig $config)
    {
        $config->setCategoryId('About Matomo');
        $config->setName('Matomo Changelog');
    }

    private function getFeed($URL) {
        $rss = new RssRenderer($URL);
        $rss->setCountPosts(1);
        $rss->showDescription(true);
        $rss->showContent(false);
        return $rss->get();
    }

    public function render()
    {   
        try {
            return $this->getFeed('https://feeds.feedburner.com/PiwikReleases');
        } catch (\Exception $e) {
            try {
            return $this->getFeed('http://feeds.feedburner.com/PiwikReleases');
            } catch (\Exception $e) {
                return $this->error($e);
            }
        }  
    }

    /**
     * @param \Exception $e
     * @return string
     */
    private function error($e)
    {
        return '<div class="pk-emptyDataTable">'
             . Piwik::translate('General_ErrorRequest', array('', ''))
             . ' - ' . $e->getMessage() . '</div>';
    }
}
