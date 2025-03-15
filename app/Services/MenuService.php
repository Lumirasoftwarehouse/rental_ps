<?php
namespace App\Services;

use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Storage;

class MenuService
{
    private $globalMenuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->globalMenuRepository = $menuRepository;
    }

    public function listMenu()
    {
        return $this->globalMenuRepository->listMenu();
    }

    public function createMenu($dataRequest)
    {
        if (isset($dataRequest['image'])) {
            // Store new image
            $imagePath = $dataRequest['image']->store('menu/image', 'public');
            $dataRequest['path'] = $imagePath;

            // Store the public URL of the image
            $dataRequest['image_url'] = Storage::url($imagePath);
        }
        return $this->globalMenuRepository->createMenu($dataRequest);
    }

    public function updateMenu($dataRequest)
    {
        $resultCekMenu = $this->globalMenuRepository->cekMenu($dataRequest['id']);
        if (isset($dataRequest['image'])) {
            // Delete old image if it exists
            if ($resultCekMenu && Storage::exists('public/' . $resultCekMenu->image)) {
                Storage::delete('public/' . $resultCekMenu->image);
            }

            // Store new image
            $imagePath = $dataRequest['image']->store('menu/image', 'public');
            $dataRequest['path'] = $imagePath;

            // Store the public URL of the image
            $dataRequest['image_url'] = Storage::url($imagePath);
        }
        return $this->globalMenuRepository->updateMenu($dataRequest);
    }

    public function deleteMenu($dataRequest)
    {
        $resultCekMenu = $this->globalMenuRepository->cekMenu($dataRequest);
        if (isset($dataRequest['image'])) {
            // Delete old image if it exists
            if ($resultCekMenu && Storage::exists('public/' . $resultCekMenu->image)) {
                Storage::delete('public/' . $resultCekMenu->image);
            }
        }
        return $this->globalMenuRepository->deleteMenu($dataRequest);
    }
}
