<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    protected $uploadPath = 'uploads/banners/';

    /**
     * Display a list of banners, filtering out any positions already used.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activeClass = 'banners';
        $pageTitle = 'Danh sách các banner';
        $listBanner = config('constant.banner');

        $banners = Banner::all();
        $existingPositions = $banners->pluck('position')->toArray();

        $filteredDataBanner = array_filter($listBanner, function ($item) use ($existingPositions) {
            return !in_array($item['position'], $existingPositions);
        });

        $data = [
            'active_class' => $activeClass,
            'page_title' => $pageTitle,
            'listBanner' => $filteredDataBanner
        ];

        return view('admin.banner.index', $data);
    }

    /**
     * Save base64 image to public directory
     *
     * @param string $base64Image
     * @return string|null
     */
    private function saveBase64Image($base64Image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            list(, $data) = explode(',', $base64Image);
            $imageData = base64_decode($data);

            // Create directory if it doesn't exist
            $uploadPath = public_path($this->uploadPath);
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }

            $filename = uniqid() . '.png';
            $path = $this->uploadPath . $filename;

            file_put_contents(public_path($path), $imageData);

            return $path;
        }

        return null;
    }

    /**
     * Create a new banner based on request input and validate images if provided.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'string',
            'url' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->to_url = $request->to_url;
        $banner->display_type = $request->display_type;
        $banner->position = $request->position;
        $banner->size = $request->size;
        $banner->is_active = 1;

        if (isset($request->images)) {
            if ($request->display_type == 'single_image') {
                $path = $this->saveBase64Image($request->images);
                $banner->image = $path;
            } else if ($request->display_type == 'multi_image' && is_array($request->images)) {
                $imagePaths = [];
                foreach ($request->images as $base64Image) {
                    $path = $this->saveBase64Image($base64Image);
                    if ($path) {
                        $imagePaths[] = $path;
                    }
                }
                $banner->image = json_encode($imagePaths);
            }
        }

        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Banner created successfully',
        ]);
    }

    /**
     * Retrieve banners and format data for DataTable display.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatable()
    {
        $banners = Banner::all()->map(function ($banner) {
            if ($banner->display_type == 'multi_image') {
                $banner->multiple_image = collect(json_decode($banner->image))->map(function ($image) {
                    return asset($image);
                });
            } else {
                $banner->image = asset($banner->image);
            }
            return $banner;
        });

        return response()->json($banners);
    }

    /**
     * Delete a banner by ID, along with its associated images from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        if ($banner->display_type == 'single_image') {
            if (File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }
        } else {
            $images = json_decode($banner->image, true);
            if ($images) {
                foreach ($images as $image) {
                    if (File::exists(public_path($image))) {
                        File::delete(public_path($image));
                    }
                }
            }
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully'
        ]);
    }

    /**
     * Update an existing banner's data and handle image updates as needed.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'string',
            'url' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        $banner->description = $request->description;
        $banner->to_url = $request->to_url;

        $existingImages = json_decode($banner->image) ?? [];

        if ($banner->display_type === 'single_image') {
            $existingImage = is_array($existingImages) ? array_values($existingImages)[0] ?? null : $existingImages;

            if (!$request->images || empty($request->images[0])) {
                if ($existingImage && File::exists(public_path($existingImage))) {
                    File::delete(public_path($existingImage));
                }
                $banner->image = null;
                $banner->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Banner updated successfully, image removed'
                ]);
            }

            $newImage = $request->images;
            if (preg_match('/^data:image\/(\w+);base64,/', $newImage)) {
                $imagePath = $this->saveBase64Image($newImage);
                if ($existingImage && File::exists(public_path($existingImage))) {
                    File::delete(public_path($existingImage));
                }
                $banner->image = $imagePath;
            }
        } else {
            if (!$request->images) {
                foreach ($existingImages as $existingImage) {
                    if (File::exists(public_path($existingImage))) {
                        File::delete(public_path($existingImage));
                    }
                }
                $banner->image = null;
                $banner->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Banner updated successfully, all images removed'
                ]);
            }

            $finalImages = array_fill(0, count($request->images), null);

            foreach ($request->images as $index => $image) {
                if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
                    $path = $this->saveBase64Image($image);
                    $finalImages[$index] = $path;
                } else {
                    $finalImages[$index] = $image;
                }
            }

            // Extract the relevant part of each image URL
            $existingImages = array_map(function ($image) {
                // Check if the image contains 'uploads/banners/'
                if (strpos($image, 'uploads/banners/') !== false) {
                    // Return the part of the string from 'uploads/banners/' onward
                    return substr($image, strpos($image, 'uploads/banners/'));
                }
                return $image; // Return the original if not found
            }, $existingImages);

            $finalImages = array_map(function ($image) {
                // Check if the image contains 'uploads/banners/'
                if (strpos($image, 'uploads/banners/') !== false) {
                    // Return the part of the string from 'uploads/banners/' onward
                    return substr($image, strpos($image, 'uploads/banners/'));
                }
                return $image; // Return the original if not found
            }, $finalImages);

            // Now proceed to check and delete files

            foreach ($existingImages as $existingImage) {
                if (!in_array($existingImage, $finalImages)) {
                    if (File::exists(public_path($existingImage))) {
                        File::delete(public_path($existingImage));
                    }
                }
            }

            $finalImages = array_values(array_filter($finalImages));
            $banner->image = json_encode($finalImages);
        }

        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully'
        ]);
    }

    /**
     * Display the specified banner details by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner không tồn tại'
            ], 404);
        }

        if ($banner->image) {
            if ($banner->display_type == 'multi_image') {
                $banner->image = collect(json_decode($banner->image))->map(function ($image) {
                    return asset($image);
                });
            } else {
                $banner->image = asset($banner->image);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }

    /**
     * Update the activation status of the specified banner by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\App\Models\Banner
     */
    public function updateStatus(Request $request, $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        $banner->is_active = ($request->is_active === 'true') ? 1 : 0;
        $banner->save();

        return $banner;
    }
}