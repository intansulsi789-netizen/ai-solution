<?php

use App\Mcp\Servers\ArtikelServer;
use Laravel\Mcp\Facades\Mcp;

/*
|--------------------------------------------------------------------------
| MCP (Claude connector)
|--------------------------------------------------------------------------
| URL connector untuk Claude: https://jogjaaisolutions.my.id/mcp/artikel
| Login memakai akun admin website (OAuth via Passport).
*/

Mcp::oauthRoutes();

Mcp::web('/mcp/artikel', ArtikelServer::class)
    ->middleware(['auth:api', 'throttle:120,1']);
