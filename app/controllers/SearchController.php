<?php

class SearchController extends BaseController {
	public function searchGet() {
		return View::make('tld.search');
	}
}

