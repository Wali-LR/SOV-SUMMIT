<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Page;
use App\Models\Service;
use Tests\TestCase;

class StorageUrlFallbackTest extends TestCase
{
    public function test_spaces_disk_has_valid_region_fallback(): void
    {
        $region = config('filesystems.disks.spaces.region');
        $this->assertNotEmpty($region);
        $this->assertIsString($region);
    }

    public function test_blog_cover_url_handles_http_and_assets_and_null(): void
    {
        $blog = new Blog;
        $this->assertNull($blog->cover_url);

        $blog->cover_image = 'https://example.com/banner.png';
        $this->assertSame('https://example.com/banner.png', $blog->cover_url);

        $blog->cover_image = 'assets/images/placeholder.jpg';
        $this->assertSame(asset('assets/images/placeholder.jpg'), $blog->cover_url);
    }

    public function test_blog_cover_url_does_not_crash_on_storage_failure(): void
    {
        // Even if spaces disk is completely broken or misconfigured
        config(['filesystems.disks.spaces.driver' => 'invalid_driver_name']);

        $blog = new Blog;
        $blog->cover_image = 'uploads/broken-image.jpg';

        $url = $blog->cover_url;
        $this->assertNotNull($url);
        $this->assertSame(asset('uploads/broken-image.jpg'), $url);
    }

    public function test_event_page_and_service_do_not_crash_on_storage_failure(): void
    {
        config(['filesystems.disks.spaces.driver' => 'invalid_driver_name']);

        $event = new Event;
        $event->cover_image = 'uploads/event.jpg';
        $this->assertSame(asset('uploads/event.jpg'), $event->cover_url);

        $page = new Page;
        $page->hero_image = 'uploads/page.jpg';
        $this->assertSame(asset('uploads/page.jpg'), $page->hero_url);

        $service = new Service;
        $service->hero_image = 'uploads/service.jpg';
        $this->assertSame(asset('uploads/service.jpg'), $service->hero_url);
    }
}
