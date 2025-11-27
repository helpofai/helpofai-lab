<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenRequest;
use App\Http\Requests\UpdatePenRequest;
use App\Models\Pen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PenController extends Controller
{
    public function index(Request $request): Response
    {
        $query = $request->user()
            ->pens()
            ->latest('updated_at');

        $pens = $query
            ->paginate(10)
            ->through(fn (Pen $pen) => [
                'id' => $pen->id,
                'title' => $pen->title,
                'slug' => $pen->slug,
                'status' => $pen->status,
                'visibility' => $pen->visibility,
                'updated_at' => $pen->updated_at?->diffForHumans(),
                'html' => $pen->html,
                'css' => $pen->css,
                'js' => $pen->js,
            ]);

        return Inertia::render('Pens/Index', [
            'pens' => $pens,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Pens/Create', [
            'defaults' => [
                'title' => 'Untitled Pen',
                'html' => '<div class="app"><h1>Hello, CodePen Clone!</h1></div>',
                'css' => ".app {\n    font-family: ui-sans-serif, system-ui;\n    padding: 2rem;\n    background: #111827;\n    color: #f9fafb;\n}",
                'js' => "console.log('Ready to build!');",
                'visibility' => 'public',
                'status' => 'draft',
                'settings' => [
                    'resources' => [
                        'css' => ['https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css'],
                        'js' => ['https://unpkg.com/vue@3/dist/vue.global.js'],
                    ],
                ],
            ],
        ]);
    }

    public function store(StorePenRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['user_id'] = $user->id;
        $validated['team_id'] = $user->currentTeam?->id;

        $pen = Pen::create($validated);

        return redirect()
            ->route('dashboard.pens.edit', $pen)
            ->with('flash.banner', 'Pen created successfully.');
    }

    public function show(Pen $pen): Response
    {
        $this->authorize('view', $pen);

        return Inertia::render('Pens/Show', [
            'pen' => $pen->only([
                'id',
                'title',
                'html',
                'css',
                'js',
                'settings',
                'status',
                'visibility',
                'slug',
            ]),
        ]);
    }

    public function edit(Pen $pen): Response
    {
        $this->authorize('update', $pen);

        return Inertia::render('Pens/Edit', [
            'pen' => $pen->only([
                'id',
                'title',
                'description',
                'slug',
                'html',
                'css',
                'js',
                'settings',
                'status',
                'visibility',
            ]),
        ]);
    }

    public function update(UpdatePenRequest $request, Pen $pen): RedirectResponse
    {
        $this->authorize('update', $pen);

        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']) . '-' . Str::random(5);

        $pen->update($validated);

        return redirect()
            ->route('dashboard.pens.edit', $pen)
            ->with('flash.banner', 'Pen updated.');
    }

    public function destroy(Pen $pen): RedirectResponse
    {
        $this->authorize('delete', $pen);

        $pen->delete();

        return redirect()
            ->route('dashboard.pens.index')
            ->with('flash.banner', 'Pen deleted.');
    }

    public function createEditor(): Response
    {
        return Inertia::render('Editor/Create');
    }
}
