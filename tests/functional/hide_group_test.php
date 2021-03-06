<?php
/**
 *
 * Advertisement management. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\ads\tests\functional;

/**
* @group functional
*/
class hide_group_test extends functional_base
{
	/**
	* {@inheritDoc}
	*/
	public function setUp()
	{
		parent::setUp();

		$this->disable_all_ads();
		$this->reset_groups();
	}

	public function tearDown()
	{
		$this->reset_groups();
	}

	public function test_ad_displays_without_hide_group()
	{
		$ad_code = $this->create_ad('above_header');

		$crawler = self::request('GET', 'index.php');

		// Confirm above header ad is present
		$this->assertContains($ad_code, $crawler->html());
	}

	public function test_ad_hides_with_hide_group()
	{
		// Hide ads for administrators
		$crawler = self::request('GET', "adm/index.php?i=-phpbb-ads-acp-main_module&mode=settings&sid={$this->sid}");
		$form_data = array(
			'hide_groups'	=> array(5),
		);
		$form = $crawler->selectButton($this->lang('SUBMIT'))->form();
		$crawler = self::submit($form, $form_data);
		$this->assertGreaterThan(0, $crawler->filter('.successbox')->count());
		$this->assertContainsLang('ACP_AD_SETTINGS_SAVED', $crawler->text());

		$ad_code = $this->create_ad('below_footer');

		$crawler = self::request('GET', 'index.php');

		// Confirm above header ad is not present
		$this->assertNotContains($ad_code, $crawler->html());
	}

	protected function reset_groups()
	{
		$crawler = self::request('GET', "adm/index.php?i=-phpbb-ads-acp-main_module&mode=settings&sid={$this->sid}");
		$form_data = array(
			'hide_groups'	=> array(),
		);
		$form = $crawler->selectButton($this->lang('SUBMIT'))->form();
		self::submit($form, $form_data);
	}
}
