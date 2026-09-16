@extends('layouts.app')

@section('title', 'Spotibai - Web Player')

@section('content')
<div class="spotibai-app">

    <!-- Top Navigation (Absolute/Fixed over the grid or part of Main) -->
    <header class="spotibai-top-bar">
        <div class="search-bar">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="What do you want to play?">
        </div>
        <div class="profile-nav">
            <div class="avatar-circle-mini">
                {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
            </div>
        </div>
    </header>

    <!-- Left Sidebar: Library -->
    <aside class="sidebar-left">
        <div class="library-header">
            <h3>📚 Your Library</h3>
            <button class="icon-btn">+</button>
        </div>
        <div class="library-filters">
            <span class="pill active">Playlists</span>
            <span class="pill">Artists</span>
        </div>
        
        <div class="playlist-scroll">
            <div class="playlist-item">
                <div class="playlist-art blue-gradient">♥️</div>
                <div class="playlist-info">
                    <h4>Liked Songs</h4>
                    <p>Playlist • 4,217 songs</p>
                </div>
            </div>
            <div class="playlist-item">
                <div class="playlist-art">🥁</div>
                <div class="playlist-info">
                    <h4>Since bai one</h4>
                    <p>Playlist</p>
                </div>
            </div>
            <div class="playlist-item">
                <div class="playlist-art">🎹</div>
                <div class="playlist-info">
                    <h4>dripst4r</h4>
                    <p>Playlist • Sound Design</p>
                </div>
            </div>
            <div class="playlist-item">
                <div class="playlist-art">🎛️</div>
                <div class="playlist-info">
                    <h4>wait a minute</h4>
                    <p>Playlist • FL Studio Projects</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-view">
        <div class="category-filters">
            <span class="pill active">All</span>
            <span class="pill">Music</span>
            <span class="pill">Podcasts</span>
        </div>

        <!-- Recent Grid (2 columns) -->
        <div class="recent-grid">
            <div class="recent-card">
                <img src="{{ asset('artwork/cover1.jpg') }}" alt="Cover">
                <span>Luking Bai</span>
            </div>
            <div class="recent-card">
                <img src="{{ asset('artwork/cover2.jpg') }}" alt="Cover">
                <span>Discover Weekly</span>
            </div>
            <div class="recent-card">
                <img src="{{ asset('artwork/cover3.jpg') }}" alt="Cover">
                <span>bai</span>
            </div>
            <div class="recent-card">
                <img src="{{ asset('artwork/cover4.jpg') }}" alt="Cover">
                <span>Zebaina</span>
            </div>
            <div class="recent-card">
                <img src="{{ asset('artwork/cover5.jpg') }}" alt="Cover">
                <span>Hip Hop Mix</span>
            </div>
            <div class="recent-card">
                <img src="{{ asset('artwork/cover6.jpg') }}" alt="Cover">
                <span>saging</span>
            </div>
        </div>

        <!-- Featured / New Release Section -->
        <div class="featured-section">
            <div class="featured-header">
                <div>
                    <span class="muted-text">New release from</span>
                    <h2>Lucki</h2>
                </div>
            </div>
            <div class="featured-banner">
                <div class="banner-content">
                    <img src="{{ asset('artwork/cover8.jpg') }}" alt="Album Art"> 
                    <div class="banner-text">
                        <span class="muted-text">EP • LUCKI</span>
                        <h1>Made My Night (Party Remixes)</h1>
                        <div class="banner-actions">
                            <button class="play-btn-large">▶</button>
                            <button class="icon-btn">⊕</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Right Sidebar: Queue / Now Playing -->
    <aside class="sidebar-right">
        <div class="queue-header">
            <h3>Queue</h3>
            <button class="icon-btn">✕</button>
        </div>
        
        <div class="now-playing-card">
            <h4>Now playing</h4>
            <div class="track-row">
                <img src="{{ asset('artwork/cover9.jpg') }}" alt="Album Art">
                <div class="track-info">
                    <span class="track-name spotibai-text">BAI NA BAI</span>
                    <span class="artist-name">Jae</span>
                </div>
            </div>
        </div>

        <div class="next-queue">
            <div class="queue-header-small">
                <h4>Next in queue</h4>
                <a href="#">Clear queue</a>
            </div>
            <div class="track-row">
                <img src="{{ asset('artwork/cover10.jpg') }}" alt="Album Art">
                <div class="track-info">
                    <span class="track-name">baby</span>
                    <span class="artist-name">di uubra</span>
                </div>
            </div>
        </div>
        
        <!-- Log Out form tucked here for functionality -->
        <div style="margin-top: auto; padding: 20px 0;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="pill" style="width: 100%; text-align: center;">Log Out</button>
            </form>
        </div>
    </aside>

    <!-- Bottom Playback Bar -->
    <footer class="bottom-player">
        <div class="player-left">
            <img src="{{ asset('artwork/cover11.jpg') }}" alt="Cover">
            <div class="track-info">
                <span class="track-name">pampampam</span>
                <span class="artist-name">kick</span>
            </div>
            <button class="icon-btn spotibai-text">♥</button>
        </div>
        
        <div class="player-center">
            <div class="playback-controls">
                <button class="icon-btn">🔀</button>
                <button class="icon-btn">⏮</button>
                <button class="play-btn-round">▶</button>
                <button class="icon-btn">⏭</button>
                <button class="icon-btn">🔁</button>
            </div>
            <div class="playback-bar">
                <span class="time">2:22</span>
                <div class="progress-bar">
                    <div class="progress" style="width: 60%;"></div>
                </div>
                <span class="time">3:51</span>
            </div>
        </div>

        <div class="player-right">
            <button class="icon-btn">🎤</button>
            <button class="icon-btn">🔈</button>
            <div class="progress-bar volume-bar">
                <div class="progress" style="width: 80%;"></div>
            </div>
        </div>
    </footer>

</div>
@endsection