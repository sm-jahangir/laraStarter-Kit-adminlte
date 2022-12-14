<?php

namespace App\Http\Controllers\Backend;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class MenuBuilderController extends Controller
{
    public function index($id)
    {
        Gate::authorize('app.menus.index');
        $menu = Menu::findOrFail($id);
        return view('backend.menus.builder', compact('menu'));
    }
    public function itemCreate($id)
    {
        Gate::authorize('app.menus.create');
        $menu = Menu::findOrFail($id);
        return view('backend.menus.item.form', compact('menu'));
    }
    public function itemStore(Request $request, $id)
    {
        $this->validate($request, [
            'divider_title' =>  'nullable|string',
            'title' =>  'nullable|string',
            'url' =>  'nullable|string',
            'target' =>  'nullable|string',
            'icon_class' =>  'nullable|string',
        ]);
        $menu = Menu::findOrFail($id);
        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => $request->type,
            'title' => $request->title,
            'divider_title' => $request->divider_title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon_class
        ]);
        notify()->success('Menu Item Successfully Added.', 'Added');
        return redirect()->route('app.menus.builder', $menu->id);
    }
    public function itemEdit($menuId, $itemId)
    {
        Gate::authorize('app.menus.edit');
        $menu = Menu::findOrFail($menuId);
        $menuItem = MenuItem::where('menu_id', $menu->id)->findOrFail($itemId);
        return view('backend.menus.item.form', compact('menu', 'menuItem'));
    }
    public function itemUpdate(Request $request, $menuId, $itemId)
    {
        $menu = Menu::findOrFail($menuId);
        $menuItem = MenuItem::where('menu_id', $menu->id)->findOrFail($itemId)->update([
            'type' => $request->type,
            'title' => $request->title,
            'divider_title' => $request->divider_title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon_class
        ]);
        notify()->success('Menu Item Successfully Updated.', 'Updated');
        return redirect()->route('app.menus.builder', $menu->id);
    }
    public function itemDestroy($menuId, $itemId)
    {
        Gate::authorize('app.menus.destroy');
        $menu = Menu::findOrFail($menuId);
        MenuItem::where('menu_id', $menu->id)->findOrFail($itemId)->delete();
        notify()->success('Menu Item Successfully Deleted.', 'Deleted');
        return redirect()->back();
    }
    public function order(Request $request, $id)
    {
        Gate::authorize('app.menus.index');
        $menuItemOrder = json_decode($request->get('order'));
        $this->orderMenu($menuItemOrder, null);
    }
    private function orderMenu(array $menuItems, $parentId)
    {
        // foreach ($menuItems as $index => $item) {
        //     $menuItem = MenuItem::findOrFail($item->id);
        //     $menuItem->update([
        //         'order' =>  $index+1,
        //         'parent_id' =>  $parentId
        //     ]);

        //     if (isset($item->childern)) {
        //         $this->orderMenu($item->childern, $menuItem->id);
        //     }
        // }

        Gate::authorize('app.menus.index');
        foreach ($menuItems as $index => $menuItem) {
            $item = MenuItem::findOrFail($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }
}
