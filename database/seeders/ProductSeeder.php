<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder; use App\Models\Product;
class ProductSeeder extends Seeder { public function run(): void {
$rows=[
[29065,'baby-pink-embroidered-tunic-set-with-schiffli-lace-sleeves-fringed-hem','Baby Pink Embroidered Tunic Set with Schiffli Lace Sleeves & Fringed Hem','stitched','ChatGPT-Image-Sep-12-2026-04_51_35-PM-1-533x800.png'],
[29032,'beige-plum-contrast-embroidered-3-piece-lawn-suit-with-dupatta','Beige & Plum Contrast Embroidered 3-Piece Lawn Suit with Dupatta','stitched','ChatGPT-Image-Sep-12-2026-04_51_25-PM-533x800.png'],
[29067,'black-chikankari-embroidered-kurti-set-with-scalloped-cutwork-borders','Black Chikankari Embroidered Kurti Set with Scalloped Cutwork Borders','stitched','ChatGPT-Image-Sep-12-2026-04_51_33-PM-533x800.png'],
[29027,'black-ethnic-printed-lawn-tunic-with-lace-trims-wide-leg-trousers','Black Ethnic Printed Lawn Tunic with Lace Trims & Wide-Leg Trousers','stitched','ChatGPT-Image-Sep-12-2026-04_51_13-PM-533x800.png'],
[29030,'deep-magenta-embroidered-kurti-set-with-lace-sleeves-fringed-hem','Deep Magenta Embroidered Kurti Set with Lace Sleeves & Fringed Hem','stitched','ChatGPT-Image-Sep-12-2026-04_51_20-PM-1-533x800.png'],
[29072,'jet-black-embroidered-tunic-shalwar-set-with-scalloped-cutwork-borders','Jet Black Embroidered Tunic & Shalwar Set with Scalloped Cutwork Borders','unstitched','ChatGPT-Image-Sep-12-2026-04_51_39-PM-1-533x800.png'],
[29024,'mint-green-floral-printed-2-piece-suit','Mint Green Floral Printed 2-Piece Suit','stitched','ChatGPT-Image-Sep-12-2026-03_12_23-PM-645x800.png'],
[29026,'mint-green-floral-printed-kurti-with-pastel-pink-grid-trousers','Mint Green Floral Printed Kurti with Pastel Pink Grid Trousers','stitched','ChatGPT-Image-Sep-12-2026-04_51_11-PM-533x800.png'],
[29029,'monochrome-abstract-printed-co-ord-set-with-button-down-tunic-wide-leg-trousers','Monochrome Abstract Printed Co-Ord Set with Button-Down Tunic & Wide-Leg Trousers','stitched','ChatGPT-Image-Sep-12-2026-04_51_18-PM-1-533x800.png'],
[29033,'off-white-embroidered-long-kurta-with-black-geometric-floral-threadwork','Off-White Embroidered Long Kurta with Black Geometric & Floral Threadwork','stitched','ChatGPT-Image-Sep-12-2026-04_51_28-PM-533x800.png'],
]; foreach($rows as [$id,$slug,$name,$cat,$file]) Product::updateOrCreate(['slug'=>$slug],['external_id'=>$id,'name'=>$name,'price_paisa'=>250000,'category'=>$cat,'image_url'=>'https://gdotcollection.com/wp-content/uploads/2026/09/'.$file,'is_active'=>true]);
} }
