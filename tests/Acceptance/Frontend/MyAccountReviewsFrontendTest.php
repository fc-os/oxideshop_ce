<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Acceptance\Frontend;

use OxidEsales\EshopCommunity\Tests\Acceptance\FrontendTestCase;

/**
 * Class MyAccountReviewsFrontendTest
 *
 * @package OxidEsales\EshopCommunity\Tests\Acceptance\Frontend
 */
class MyAccountReviewsFrontendTest extends FrontendTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->activateTheme('flow');
    }

    /**
     * @group flow-theme
     */
    public function testMyAccountReviews()
    {
        $this->insertRating();
        $this->openReviewsPage();
        $this->checkReviewListCount(10);
        $this->checkReviewMenuCount(11);
        $this->clickNextPaginationPage();
        $this->deleteReview();
        $this->checkReviewMenuCount(10);
        $this->checkReviewListCount(10);
    }

    private function openReviewsPage()
    {
        $this->setConfigToAllowUserManageOwnReviews();

        $this->openShop();
        $this->loginInFrontendFlowTheme("example_test@oxid-esales.dev", "useruser");
        $this->openMyAccountPage();

        $this->clickAndWait("//a[@title='%MY_REVIEWS%']");
    }

    private function insertRating()
    {
        $sql = "REPLACE INTO `oxratings` (`OXID`, `OXSHOPID`, `OXUSERID`, `OXTYPE`, `OXOBJECTID`, `OXRATING`, 
                `OXTIMESTAMP`) VALUES
                ('testrating2', 1, 'testuser', 'oxrecommlist', 'testrecomm',  4,         '2009-11-10 12:18:30');";

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $database->execute($sql);
    }

    /**
     * @param int $expectedReviewsCount
     */
    private function checkReviewMenuCount($expectedReviewsCount)
    {
        $actualReviewsCount = $this->getText("//nav[@id='account_menu']//span[@class='badge']");

        $this->assertEquals(
            $expectedReviewsCount,
            $actualReviewsCount,
            "Expected to see to number $expectedReviewsCount in the menu but number $actualReviewsCount shown."
        );
    }

    private function clickNextPaginationPage()
    {
        $paginationLocator = "//ol[@class='pagination pagination-sm lineBox']";
        $this->assertElementPresent($paginationLocator);
        $this->click($paginationLocator . "/li[@class='next']/a");
    }

    /**
     * @param int $expectedReviewsCount
     */
    private function checkReviewListCount($expectedReviewsCount)
    {
        $reviewsCount = $this->getXpathCount("//div[starts-with(@id,'reviewName_')]");

        $this->assertEquals(
            $expectedReviewsCount,
            $reviewsCount,
            "$expectedReviewsCount reviews expected but $reviewsCount shown."
        );
    }

    private function deleteReview()
    {
        $this->clickAndWait("//button[@data-target='#delete_review_1']");
        $this->clickAndWait("//form[@id='remove_review_1']//button[@type='submit']");
    }

    private function openMyAccountPage()
    {
        $this->click("//div[contains(@class, 'service-menu')]/button");
        $this->waitForItemAppear("services");
        $this->clickAndWait("//ul[@id='services']/li/a");
    }

    private function setConfigToAllowUserManageOwnReviews()
    {
        $this->callShopSC(
            "oxConfig",
            null,
            null,
            [
                'blAllowUsersToManageTheirReviews' => [
                    "type" => "bool",
                    "value" => true,
                ],
            ]
        );
    }
}
