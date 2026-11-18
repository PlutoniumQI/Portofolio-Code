<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesFields;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use HandlesFields;

    public function edit(): View
    {
        return view('admin.profile', [
            'record' => Profile::current(),
            'groups' => config('admin.profile'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $groups = config('admin.profile');
        $fields = $this->allFields($groups);
        $record = Profile::current();

        $this->mergeCheckboxes($request, $fields);
        $validated = $request->validate($this->fieldRules($fields, $record));

        $record->update($this->normalizeFields($fields, $validated));

        return back()->with('status', 'Profile & CV saved.');
    }
}
