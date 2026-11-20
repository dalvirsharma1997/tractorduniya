<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * DEMO DATA — generates simple solid-gradient placeholder banner images
 * locally (no external/competitor imagery) so the homepage slider has
 * something to show out of the box. Replace via admin before go-live.
 */
class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            ['title' => 'Compare 100+ Tractor Models', 'subtitle' => 'Find the right HP, price, and brand for your farm.', 'link' => '/tractors', 'button' => 'Browse Tractors', 'from' => [21, 128, 61], 'to' => [5, 46, 22]],
            ['title' => 'Buy or Sell Used Tractors', 'subtitle' => 'Verified listings reviewed by our team before they go live.', 'link' => '/used-tractors', 'button' => 'Explore Listings', 'from' => [202, 138, 4], 'to' => [120, 53, 15]],
            ['title' => 'Find a Dealer Near You', 'subtitle' => 'Authorised dealers across every state, verified and listed.', 'link' => '/dealers', 'button' => 'Find Dealers', 'from' => [30, 58, 138], 'to' => [15, 23, 42]],
        ];

        foreach ($slides as $i => $slide) {
            $path = "banners/demo-slide-{$i}.png";

            if (! Storage::disk('public')->exists($path)) {
                $image = $this->gradientImage($slide['from'], $slide['to']);
                Storage::disk('public')->put($path, $image);
            }

            Banner::updateOrCreate(
                ['title' => $slide['title']],
                [
                    'subtitle' => $slide['subtitle'],
                    'image_path' => $path,
                    'link_url' => $slide['link'],
                    'button_text' => $slide['button'],
                    'sort_order' => $i,
                    'is_active' => true,
                ]
            );
        }
    }

    private function gradientImage(array $from, array $to): string
    {
        $width = 1600;
        $height = 600;
        $im = imagecreatetruecolor($width, $height);

        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $r = (int) ($from[0] + ($to[0] - $from[0]) * $ratio);
            $g = (int) ($from[1] + ($to[1] - $from[1]) * $ratio);
            $b = (int) ($from[2] + ($to[2] - $from[2]) * $ratio);
            $color = imagecolorallocate($im, $r, $g, $b);
            imageline($im, 0, $y, $width, $y, $color);
        }

        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        imagedestroy($im);

        return $data;
    }
}
