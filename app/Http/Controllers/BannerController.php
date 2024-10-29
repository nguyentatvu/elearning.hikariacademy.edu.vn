<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
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

        $data = [];
        $data['active_class'] = $activeClass;
        $data['page_title'] = $pageTitle;
        $data['listBanner'] = $filteredDataBanner;

        return view('admin.banner.index', $data);
    }

    /**
     * Create a new banner based on request input and validate images if provided.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'string',
            'url' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $isActive = 1;

        // Create new banner instance
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->to_url = $request->to_url;
        $banner->display_type = $request->display_type;
        $banner->position = $request->position;
        $banner->size = $request->size;
        $banner->is_active = $isActive;

        // Handle images
        $imagePaths = [];

        if (isset($request->images)) {
            if ($request->display_type == 'single_image') {
                // Handle single image case
                $base64Image = $request->images;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    // Split the Base64 string to get the data part
                    list(, $data) = explode(',', $base64Image);
                    // Decode Base64 data
                    $imageData = base64_decode($data);

                    // Generate a unique filename for the image
                    $filename = uniqid() . '.png';

                    // Define the path to save the image
                    $path = 'banners/' . $filename;

                    // Save the image to the storage
                    Storage::disk('public')->put($path, $imageData);

                    // Save the relative image path
                    $banner->image = $path; // Only save the relative path here
                }
            } else if ($request->display_type == 'multi_image' && is_array($request->images)) {
                // Handle multiple images case
                foreach ($request->images as $base64Image) {
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                        // Split the Base64 string to get the data part
                        list(, $data) = explode(',', $base64Image);
                        // Decode Base64 data
                        $imageData = base64_decode($data);

                        // Generate a unique filename for the image
                        $filename = uniqid() . '.png';

                        // Define the path to save the image
                        $path = 'banners/' . $filename;

                        // Save the image to the storage
                        Storage::disk('public')->put($path, $imageData);

                        // Add the relative image path to the array
                        $imagePaths[] = $path; // Store relative path
                    }
                }

                // Save multiple image paths as JSON
                $banner->image = json_encode($imagePaths);
            }
        }

        // Save the Banner
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
                    return asset('storage/' . $image);
                });
            } else {
                $banner->image = asset('storage/' . $banner->image);
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

        // If there are images, you may want to delete them from the storage
        if ($banner->display_type == 'single_image') {
            $imagePath = str_replace(asset('storage/'), '', $banner->image);
            Storage::disk('public')->delete($imagePath);
        } else {
            $images = json_decode($banner->image, true);
            if ($images) {
                foreach ($images as $image) {
                    $imagePath = str_replace(asset('storage/'), '', $image);
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        // Delete the banner record from the database
        $banner->delete();

        // Return a success response
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

        // Find the banner by ID
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        // Update basic banner fields
        $banner->description = $request->description;
        $banner->to_url = $request->to_url;

        // Get existing images
        $existingImages = json_decode($banner->image) ?? [];

        // Handle single image case
        if ($banner->display_type === 'single_image') {
            // Get the existing single image
            $existingImage = is_array($existingImages) ? array_values($existingImages)[0] ?? null : $existingImages;

            // If no new image provided
            if (!$request->images || empty($request->images[0])) {
                // Delete existing image if it exists
                if ($existingImage && Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }
                $banner->image = null;
                $banner->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Banner updated successfully, image removed'
                ]);
            }
            // Process new single image
            $newImage = $request->images;
            $imagePath = null;

            if (preg_match('/^data:image\/(\w+);base64,/', $newImage, $type)) {
                // Handle Base64 image
                list(, $data) = explode(',', $newImage);
                $imageData = base64_decode($data);
                $filename = uniqid() . '.png';
                $imagePath = 'banners/' . $filename;

                Storage::disk('public')->put($imagePath, $imageData);
            } else {
                // Handle image URL
                $imagePath = $newImage;
            }

            // Delete old image if it's different
            if ($existingImage && $existingImage !== $imagePath && Storage::disk('public')->exists($existingImage)) {
                Storage::disk('public')->delete($existingImage);
            }
            $banner->image = $imagePath;

            // Update with new image
            $banner->save();

            return response()->json([
                'success' => true,
                'message' => 'Banner single image updated successfully'
            ]);
        } else {

            // Create final image array with the same size as input
            $finalImages = array_fill(0, count($request->images), null);

            // Process images array maintaining order from input
            foreach ($request->images as $index => $image) {
                if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
                    // Handle Base64 images
                    list(, $data) = explode(',', $image);
                    $imageData = base64_decode($data);
                    $filename = uniqid() . '.png';
                    $path = 'banners/' . $filename;

                    Storage::disk('public')->put($path, $imageData);
                    $finalImages[$index] = $path; // Store at the specified index
                } else {
                    // Handle image URLs at the specified index
                    $finalImages[$index] = $image;
                }
            }

            // Compare with existing images to determine what to delete
            foreach ($existingImages as $existingImage) {
                if (!in_array($existingImage, $finalImages)) {
                    if (Storage::disk('public')->exists($existingImage)) {
                        Storage::disk('public')->delete($existingImage);
                    }
                }
            }

            // Remove any null values that might exist
            $finalImages = array_values(array_filter($finalImages));

            // Update banner with final image array
            $banner->image = json_encode($finalImages);
        }

        // Handle multiple images case (original logic)
        if (!$request->images) {
            // Delete all existing images
            foreach ($existingImages as $existingImage) {
                if (Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }
            }
            $banner->image = null;
            $banner->save();
            return response()->json([
                'success' => true,
                'message' => 'Banner updated successfully, all images removed'
            ]);
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
        if ($banner) {
            if ($banner->image) {
                if ($banner->display_type == 'multi_image') {
                    $banner->image = collect(json_decode($banner->image))->map(function ($image) {
                        return asset('storage/' . $image);
                    });
                } else {
                    $banner->image = asset('storage/' . $banner->image);
                }
            }
        }

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner không tồn tại'
            ], 404);
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
        $isChecked = ($request->is_active === 'true') ? 1 : 0;

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404);
        }

        $banner->is_active = $isChecked;

        $banner->save();

        return $banner;
    }
}
