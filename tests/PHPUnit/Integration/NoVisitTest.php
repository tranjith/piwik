<?php
/**
 * Piwik - Open source web analytics
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/**
 * testing various wrong Tracker requests and check that they behave as expected:
 * not throwing errors and not recording data.
 * API will archive and output empty stats.
 */
class Test_Piwik_Integration_NoVisit extends IntegrationTestCase
{
    public static $fixture = null; // initialized below class definition

    /**
     * @dataProvider getApiForTesting
     * @group        Integration
     * @group        NoVisit
     */
    public function testApi($api, $params)
    {
        $this->runApiTests($api, $params);
    }

    public function getApiForTesting()
    {
        $seoApi = 'SEO.getSEOStats';
        
        // this will output empty XML result sets as no visit was tracked
        return array(
            array('all', array('idSite' => self::$fixture->idSite,
                               'date'   => self::$fixture->dateTime)),
            array('all', array('idSite'       => self::$fixture->idSite,
                               'date'         => self::$fixture->dateTime,
                               'periods'      => array('day', 'week'),
                               'setDateLastN' => true,
                               'testSuffix'   => '_PeriodIsLast')),
            
            // seo metrics should still appear when there are no visits
            array($seoApi, array('idSite'       => self::$fixture->idSite,
                                 'date'         => self::$fixture->dateTime,
                                 'periods'      => array('day', 'week'))),
            array($seoApi, array('idSite'                 => self::$fixture->idSite,
                                 'date'                   => self::$fixture->dateTime,
                                 'periods'                => array('day', 'week'),
                                 'testSuffix'             => '_full',
                                 'otherRequestParameters' => array('full' => 1))),
            
            array('SEO.getSEOStatsForUrl', array('idSite'                 => self::$fixture->idSite,
                                                 'date'                   => self::$fixture->dateTime,
                                                 'periods'                => 'day',
                                                 'otherRequestParameters' => array('url' => 'http://piwik.org'))),
        );
    }

    public function getOutputPrefix()
    {
        return 'noVisit';
    }
}

Test_Piwik_Integration_NoVisit::$fixture = new Test_Piwik_Fixture_InvalidVisits();

