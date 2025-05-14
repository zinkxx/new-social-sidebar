<?php
// Admin menüsüne sayfa ekle
function sm_sidebar_menu_page()
{
    add_menu_page(
        'Social Media Sidebar',   // Sayfa başlığı
        'Social Sidebar',         // Menü adı
        'manage_options',         // Kullanıcı izinleri
        'sm-sidebar-settings',    // Sayfa slug
        'sm_sidebar_settings_page', // Sayfa içeriği işlevi
        'dashicons-share',        // Menü ikonu
        80
    );
}
add_action('admin_menu', 'sm_sidebar_menu_page');

// Ayar sayfası içeriği
function sm_sidebar_settings_page()
{
?>
    <div class="wrap">
        <h1>Social Media Sidebar Ayarları</h1>
        <form method="post" action="options.php">
            <?php settings_fields('sm_sidebar_settings_group'); ?>
            <?php do_settings_sections('sm_sidebar_settings_group'); ?>

            <h2>Sosyal Medya Hesapları</h2>
            <?php
            $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
            foreach ($platforms as $platform) {
                $urls = get_option("sm_sidebar_{$platform}_urls", []);
                echo "<h4>" . ucfirst($platform) . "</h4>";
                echo '<div class="sm-platform-group">';
                if (!empty($urls)) {
                    foreach ($urls as $url) {
                        echo "<input type='url' name='sm_sidebar_{$platform}_urls[]' value='" . esc_attr($url) . "' placeholder='URL girin' style='width: 100%; margin-bottom: 6px;' />";
                    }
                }
                echo "<input type='url' name='sm_sidebar_{$platform}_urls[]' placeholder='Yeni URL ekleyin' style='width: 100%; margin-bottom: 12px;' />";
                echo '</div>';
            }
            ?>

            <h2>Görünüm Ayarları</h2>
            <label>Konum:
                <select name="sm_sidebar_position">
                    <option value="left" <?php selected(get_option('sm_sidebar_position'), 'left'); ?>>Sol</option>
                    <option value="right" <?php selected(get_option('sm_sidebar_position'), 'right'); ?>>Sağ</option>
                    <option value="bottom" <?php selected(get_option('sm_sidebar_position'), 'bottom'); ?>>Alt</option>
                </select>
            </label>
            <br><br>
            <label>
                <input type="checkbox" name="sm_sidebar_dark_mode" value="1" <?php checked(get_option('sm_sidebar_dark_mode'), 1); ?>>
                Karanlık Mod
            </label>
            <br><br>
            <input type="submit" class="button button-primary" value="Kaydet">
        </form>
    </div>
<?php
}
