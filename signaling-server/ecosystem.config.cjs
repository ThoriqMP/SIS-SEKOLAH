module.exports = {
  apps: [
    {
      name: 'sis-sekolah-signaling',
      script: './server.js',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '256M',
      env: {
        NODE_ENV: 'production',
        PORT: 3000,
        CORS_ORIGIN: 'https://sissekolah.vercel.app'
      }
    }
  ]
};
