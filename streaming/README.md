# CCTV RTSP to HLS

Browser cannot play `rtsp://` directly. Use MediaMTX as the gateway.

## Setup on Windows

1. Download the Windows MediaMTX release from https://github.com/bluenviron/mediamtx/releases.
2. Extract `mediamtx.exe` into this directory.
3. Copy `mediamtx.yml.example` to `mediamtx.yml`.
4. Add one `camera-{id}` path for each row in the `cctv` table.
5. Replace each example `source` with the camera RTSP URL.
6. Start the gateway from this directory:

```powershell
.\mediamtx.exe mediamtx.yml
```

7. In the project `env` file, enable and adjust:

```ini
CCTV_HLS_BASE_URL = 'http://127.0.0.1:8888'
CCTV_WEBRTC_BASE_URL = 'http://127.0.0.1:8889'
CCTV_WEBRTC_PATH_TEMPLATE = 'camera-{id}'
```

For access from another computer, use the server IP instead of `127.0.0.1`, for example `http://10.201.43.242:8888`.

The CCTV detail page then loads:

```text
http://127.0.0.1:8888/camera-{id}/index.m3u8
```

The WebRTC button uses the MediaMTX player page:

```text
http://127.0.0.1:8889/camera-{id}?autoplay=true
```

For an existing external WebRTC gateway, set the base URL and path directly, for example:

```ini
CCTV_WEBRTC_BASE_URL = 'http://10.201.11.114:8889'
CCTV_WEBRTC_PATH_TEMPLATE = 'CCTV0320260411'
```

Keep the MediaMTX port reachable from the browser and allow it through Windows Firewall. Do not expose the RTSP URL or camera password in frontend code.
