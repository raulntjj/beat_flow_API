<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder {
    public function run() {
        // Usuários
        User::insert([
            ['name' => 'Raul', 'last_name' => 'De Oliveira', 'email' => 'raulntjj@gmail.com', 'user' => 'raulntjj', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Hello World!', 'is_private' => false],
            ['name' => 'Alice', 'last_name' => 'Smith', 'email' => 'alice@gmail.com', 'user' => 'alice', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Music is life!', 'is_private' => false],
            ['name' => 'Bob', 'last_name' => 'Johnson', 'email' => 'bob@gmail.com', 'user' => 'bob', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Rock and Blues.', 'is_private' => true],
            ['name' => 'Charlie', 'last_name' => 'Brown', 'email' => 'charlie@gmail.com', 'user' => 'charlie', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Guitarist.', 'is_private' => false],
            ['name' => 'Diana', 'last_name' => 'Prince', 'email' => 'diana@gmail.com', 'user' => 'diana', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Singer-songwriter.', 'is_private' => true],
            ['name' => 'Eve', 'last_name' => 'Taylor', 'email' => 'eve@gmail.com', 'user' => 'eve', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Jazz lover.', 'is_private' => false],
            ['name' => 'Frank', 'last_name' => 'Castle', 'email' => 'frank@gmail.com', 'user' => 'frank', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Aspiring DJ.', 'is_private' => true],
            ['name' => 'Grace', 'last_name' => 'Hopper', 'email' => 'grace@gmail.com', 'user' => 'grace', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Pianist and composer.', 'is_private' => false],
            ['name' => 'Hank', 'last_name' => 'Hill', 'email' => 'hank@gmail.com', 'user' => 'hank', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/profile_photo.jpg', 'bio' => 'Metalhead.', 'is_private' => true],
            ['name' => 'Ivy', 'last_name' => 'Walker', 'email' => 'ivy@gmail.com', 'user' => 'ivy', 'password' => Hash::make('12345678'), 'profile_photo_path' => 'beatflow/placeholder/pprofile_photo.jpg', 'bio' => 'Electronic music producer.', 'is_private' => false],
        ]);

        // Gêneros
        DB::table('genres')->insert([
            ['name' => 'Rock', 'slug' => 'rock'],
            ['name' => 'Jazz', 'slug' => 'jazz'],
            ['name' => 'Pop', 'slug' => 'pop'],
            ['name' => 'Hip-Hop', 'slug' => 'hip-hop'],
            ['name' => 'Classical', 'slug' => 'classical'],
            ['name' => 'Electronic', 'slug' => 'electronic'],
            ['name' => 'Reggae', 'slug' => 'reggae'],
            ['name' => 'Blues', 'slug' => 'blues'],
            ['name' => 'Country', 'slug' => 'country'],
            ['name' => 'Metal', 'slug' => 'metal'],
        ]);

        // Seguir Usuários
        DB::table('follows')->insert([
            ['follower_id' => 1, 'followed_id' => 2],
            ['follower_id' => 1, 'followed_id' => 3],
            ['follower_id' => 2, 'followed_id' => 4],
            ['follower_id' => 2, 'followed_id' => 5],
            ['follower_id' => 3, 'followed_id' => 1],
            ['follower_id' => 4, 'followed_id' => 6],
            ['follower_id' => 5, 'followed_id' => 7],
            ['follower_id' => 6, 'followed_id' => 8],
            ['follower_id' => 7, 'followed_id' => 9],
            ['follower_id' => 8, 'followed_id' => 10],
        ]);

        // Postagens
        DB::table('posts')->insert([
            ['user_id' => 1, 'content' => 'First post from Raul.', 'visibility' => 'public', 'media_type' => 'image', 'media_path' => 'post1.jpg', 'created_at' => now()],
            ['user_id' => 2, 'content' => 'Alice shares her thoughts.', 'visibility' => 'public', 'media_type' => 'image', 'media_path' => 'post2.jpg', 'created_at' => now()],
            ['user_id' => 3, 'content' => 'Bob’s latest track.', 'visibility' => 'followers', 'media_type' => 'audio', 'media_path' => 'post3.mp3', 'created_at' => now()],
            ['user_id' => 4, 'content' => 'Charlie playing live.', 'visibility' => 'public', 'media_type' => 'image', 'media_path' => 'post4.mp4', 'created_at' => now()],
            ['user_id' => 5, 'content' => 'Diana’s acoustic session.', 'visibility' => 'public', 'media_type' => 'audio', 'media_path' => 'post5.mp3', 'created_at' => now()],
        ]);

        // Comentários
        DB::table('comments')->insert([
            ['post_id' => 1, 'user_id' => 2, 'content' => 'Nice post, Raul!', 'created_at' => now()],
            ['post_id' => 2, 'user_id' => 3, 'content' => 'Love it, Alice!', 'created_at' => now()],
            ['post_id' => 3, 'user_id' => 4, 'content' => 'Great track, Bob!', 'created_at' => now()],
            ['post_id' => 4, 'user_id' => 5, 'content' => 'Amazing performance, Charlie!', 'created_at' => now()],
            ['post_id' => 5, 'user_id' => 6, 'content' => 'Beautiful session, Diana.', 'created_at' => now()],
        ]);

        // Postagens Compartilhadas
        DB::table('shared_posts')->insert([
            ['post_id' => 1, 'user_id' => 3, 'comment' => 'Check this out!', 'created_at' => now()],
            ['post_id' => 2, 'user_id' => 4, 'comment' => 'Awesome!', 'created_at' => now()],
            ['post_id' => 3, 'user_id' => 5, 'comment' => 'Fantastic track!', 'created_at' => now()],
        ]);

        // Feeds
        DB::table('feeds')->insert([
            ['post_id' => 2, 'created_at' => now()],
            ['post_id' => 3, 'created_at' => now()],
            ['shared_post_id' => 1, 'created_at' => now()],
        ]);

        // Notificações
        DB::table('notifications')->insert([
            ['user_id' => 1, 'type' => 'like', 'is_read' => 0, 'content' => 'Alice liked your post.', 'created_at' => now()],
            ['user_id' => 2, 'type' => 'comment', 'is_read' => 0, 'content' => 'Bob commented on your post.', 'created_at' => now()],
        ]);

        // Post Engagements
        DB::table('post_engagements')->insert([
            ['post_id' => 1, 'user_id' => 2, 'type' => 'like', 'created_at' => now()],
            ['post_id' => 2, 'user_id' => 3, 'type' => 'like', 'created_at' => now()],
        ]);

        // Roles
        DB::table('roles')->insert([
            ['name' => 'Admin', 'created_at' => now()],
            ['name' => 'User', 'created_at' => now()],
        ]);

        // Permissions
        DB::table('permissions')->insert([
            ['name' => 'edit-posts', 'created_at' => now()],
            ['name' => 'delete-posts', 'created_at' => now()],
        ]);

        // Permission Roles
        DB::table('permission_roles')->insert([
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
        ]);

        // User Roles
        DB::table('user_roles')->insert([
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
        ]);

        // User Genres
        DB::table('user_genres')->insert([
            ['user_id' => 1, 'genre_id' => 1],
            ['user_id' => 2, 'genre_id' => 2],
        ]);
    }
}
