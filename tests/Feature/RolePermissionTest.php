<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Link;
use App\Models\Category;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->editorRole = Role::create(['name' => 'Editor', 'slug' => 'editor']);
        $this->viewerRole = Role::create(['name' => 'Viewer', 'slug' => 'viewer']);
    }

    public function test_admin_can_access_everything()
    {
        $admin = User::factory()->create(['email' => 'admin@odin.com']);
        $admin->roles()->attach($this->adminRole);
        $this->actingAs($admin);

        $response = $this->post(route('links.store'), [
            'title' => 'Admin Link',
            'url' => 'https://admin.com',
            'category_id' => Category::create(['name' => 'Tech', 'user_id' => $admin->id])->id,
        ]);

        $response->assertRedirect(route('dashboard'));

        
        $this->get(route('activity_logs.index'))->assertStatus(200);

       
        $this->get(route('users.index'))->assertStatus(200);
    }

    public function test_editor_can_manage_own_resources_but_restricted_elsewhere()
    {
        $editor = User::factory()->create(['email' => 'editor@odin.com']);
        $editor->roles()->attach($this->editorRole);
        $this->actingAs($editor);

    
        $response = $this->post(route('links.store'), [
            'title' => 'Editor Link',
            'url' => 'https://editor.com',
            'category_id' => Category::create(['name' => 'Editor Cat', 'user_id' => $editor->id])->id,
        ]);
        $response->assertRedirect(route('dashboard'));

        
        $this->get(route('activity_logs.index'))->assertStatus(403);

        
        $this->get(route('users.index'))->assertStatus(403);
    }

    public function test_viewer_can_only_read()
    {
        $viewer = User::factory()->create(['email' => 'viewer@odin.com']);
        $viewer->roles()->attach($this->viewerRole);
        $category = Category::create(['name' => 'Viewer Cat', 'user_id' => $viewer->id]);
        $this->actingAs($viewer);

        
        $response = $this->post(route('links.store'), [
            'title' => 'Viewer Link',
            'url' => 'https://viewer.com',
            'category_id' => $category->id,
        ]);
        $response->assertStatus(403);

        
        $response = $this->delete(route('categories.destroy', $category->id));
        $response->assertStatus(403);
    }
}
