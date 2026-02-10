@servers(['web' => 'root@monitor'])

@story('full-deploy')
    git-update
    set-permissions
    install-dependencies
    build-assets
    migrate-database
    clear-caches
    restart-services
@endstory

@task('git-update', ['on' => ['web']])
    cd /var/www/monitor-precos-v2

    sudo -u web01 git fetch origin main
    sudo -u web01 git checkout main
    sudo -u web01 git reset --hard "origin/main"
@endtask

@task('set-permissions', ['on' => ['web']])
    cd /var/www/monitor-precos-v2

    chown -R web01: .
@endtask

@task('install-dependencies', ['on' => ['web']])
    cd /var/www/monitor-precos-v2

    sudo -u web01 php8.4 /usr/bin/composer install --no-dev -o
@endtask

@task('build-assets', ['on' => ['web']])
    cd /var/www/monitor-precos-v2
    
    # Carrega o NVM e usa a versão default
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    
    npm install && npm run build
@endtask

@task('migrate-database', ['on' => ['web']])
    cd /var/www/monitor-precos-v2

    php8.4 artisan migrate --force
@endtask

@task('clear-caches', ['on' => ['web']])
    cd /var/www/monitor-precos-v2

    php8.4 artisan optimize:clear
    php8.4 artisan app:cache-top-discounted-products
@endtask

@task('restart-services', ['on' => ['web']])
    cd /var/www/monitor-precos-v2

    php8.4 artisan queue:restart
    php8.4 artisan schedule:clear
@endtask