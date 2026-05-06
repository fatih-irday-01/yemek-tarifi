<?php

declare(strict_types=1);

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('misafir kullanıcı yükleme sayfasına erişemez', function (): void {
    $this->get(route('recipes.create'))->assertRedirect(route('login'));
});

test('misafir kullanıcı geçmiş sayfasına erişemez', function (): void {
    $this->get(route('history'))->assertRedirect(route('login'));
});

test('giriş yapmış kullanıcı yükleme sayfasına erişebilir', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('recipes.create'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Recipe/Upload'));
});

test('giriş yapmış kullanıcı geçmiş sayfasına erişebilir', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('history'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Recipe/History'));
});

test('dashboard sayfası kimlik doğrulama gerektirir', function (): void {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('giriş yapmış kullanıcı dashboard sayfasına erişebilir', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Dashboard'));
});
