<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Subdomain
{
    public const SUBDOMAIN_COENERGIA = "coenergia";
    public const SUBDOMAIN_AOM = "aom";

    public static function getTitle()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "Enertec",
            self::SUBDOMAIN_COENERGIA => "Coenergia",
            default => "Enertec",
        };
    }

    public static function getIcon()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "https://enerteclatam.com/media/wkvhaio3/favicon.png",
            self::SUBDOMAIN_COENERGIA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/coenergia-icon.jpeg?response-content-disposition=inline&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEN3%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMSJGMEQCICM95IdN7BOXdSurTvk%2Fy2iIItINGB4x5bY3SjzufV7nAiBRbw3o8n5IoOO7SV0OJvd5%2FE7R5qzuVbo%2FgogCM4GLyirtAgj2%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDk2NDM3MDY0NTg3NCIMJdaGW4JUDRAeWvF0KsECvJDrQuILosHzX90z4S9AobKPCzURt7CULBRtcb7hqliIHbXaXVblsyx4btcR8IxUNZESuvYr7nwH7P2HLT%2BTa8L6GhGbPfnHPuU9Gr9uujdE%2BCmhuttbLA60fKdcOs4r44wzIO%2Btqda7CBdZEFYSn2ZyyDDajM1NEMJZzYuIqGhnY%2FE5e3Z%2FLxKpnISYIPYAdNdCb7CzkIFJXiorVnIMAiOGaY4WVPhIHimOBpU0rDTXe%2FgLkQptEks0taR2Laxdu4SZ6uhp%2BqQz04BEx%2BrjQKspLnKIfB0exSrshZjL2h%2BC%2FQGUgGLr9L8g9RITYETTj7oevo7271H6K6Q6R6elxmSdOCLmFyIupbV2Pi2m3DkKDO%2FpWg3E4WYDg3a3%2BPBFQUTt%2BAaQ2ETwOBZ0T9ym9aG%2BnXVjnkW9dgfPYSRZQ2W8MNvH7ZUGOrQCLih7YCw000b4QrzTK%2BeM5r0wz2qN6dCzLFw6YZKAGe%2FXWUBuSk2O53S2P8lOBb1bkB4byESuV5cGDBfhugKNHJd8L%2B39wEVAy1hHfJIH9OtoQyjRScwAUsoddCee7CBZF4g3f6JwB6HbUxrhqFOLdzk%2FdETxFuKXvdkfkxy0CPiq4WeeGRaDQxt5R%2F1%2FKOxoDLAALnCXwfbIZeNZ3cstwiQD5ygFHeI6E1ePhUjNdQ1wkinCVBbMFkXaYbtf4Rr95SvURyr99VklM3BIcIHyfJCvSkj%2Bgm%2BAIYWwBqkuu2dWoPHYOv9Z2SXK3AtjvmP8Bh2abepnmDAvyrmuKqfrUnA1MUPup6utdCKmvor7NZtshEhWM96MMqZ8rGMWdSJ1rX6rztMwgbsmZdzGqnnVDQxMVOk%3D&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20220628T202719Z&X-Amz-SignedHeaders=host&X-Amz-Expires=300&X-Amz-Credential=ASIA6BCHYINZAV63LJHF%2F20220628%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Signature=bd22f04cfd4779c1cb6438c57d96691156a5a5de83ebb3a371e31dc4d4ed231b",
            default => "https://enerteclatam.com/media/wkvhaio3/favicon.png",
        };
    }
}
