<?php

class RoutesTest extends TestCase {

	public function testGetHomeRoute()
	{
		$this->get('/');
		$this->assertEquals('200', $this->response->status());
	}

	public function testGetPlaylistRoute()
	{
		$this->get('/playlist');
		
		$this->assertEquals('200', $this->response->status());
	}

}
