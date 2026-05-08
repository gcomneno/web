#!/usr/bin/env bash
set -euo pipefail

# Transcribe a Laravel lesson video using ffmpeg + local Whisper wrapper.
#
# Usage from repo root:
#   ./laravel-lab/scripts/transcribe-laravel-lesson.sh /path/to/video.mp4 lesson-02
#   ./laravel-lab/scripts/transcribe-laravel-lesson.sh --no-compress /path/to/video.mp4 lesson-02
#
# Output:
#   laravel-lab/lessons/<lesson-name>/transcript.txt
#
# Notes:
#   - MP4 files are stored only under laravel-lab/_work/ when compression is enabled.
#   - _work/ must stay local and ignored by git.

NO_COMPRESS=0

if [ "${1:-}" = "--no-compress" ]; then
    NO_COMPRESS=1
    shift
fi

if [ "$#" -lt 1 ] || [ "$#" -gt 2 ]; then
    echo "Usage: $0 [--no-compress] VIDEO_FILE [LESSON_NAME]" >&2
    exit 2
fi

VIDEO_FILE="$1"
LESSON_NAME="${2:-lesson}"

if [ ! -f "$VIDEO_FILE" ]; then
    echo "Errore: file video non trovato: $VIDEO_FILE" >&2
    exit 1
fi

if ! command -v ffmpeg >/dev/null 2>&1; then
    echo "Errore: ffmpeg non trovato." >&2
    exit 1
fi

if [ ! -d "$HOME/.venvs/whisper" ]; then
    echo "Errore: venv Whisper non trovata in $HOME/.venvs/whisper" >&2
    exit 1
fi

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
LARAVEL_LAB_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
WORK_DIR="$LARAVEL_LAB_ROOT/_work"
LESSON_DIR="$LARAVEL_LAB_ROOT/lessons/$LESSON_NAME"
RAW_TRANSCRIPT_DIR="$WORK_DIR/transcripts/$LESSON_NAME"

SMALL_VIDEO="$WORK_DIR/${LESSON_NAME}-small.mp4"

if [ "$NO_COMPRESS" -eq 1 ]; then
    VIDEO_FOR_WHISPER="$VIDEO_FILE"
else
    VIDEO_FOR_WHISPER="$SMALL_VIDEO"
fi

RAW_TXT="$RAW_TRANSCRIPT_DIR/$(basename "$VIDEO_FOR_WHISPER" .mp4).txt"
FINAL_TXT="$LESSON_DIR/transcript.txt"

mkdir -p "$WORK_DIR" "$RAW_TRANSCRIPT_DIR" "$LESSON_DIR"

echo "==> Laravel lab root: $LARAVEL_LAB_ROOT"
echo "==> Lesson name:      $LESSON_NAME"
echo "==> No compress:      $NO_COMPRESS"
echo "==> Video sorgente:   $VIDEO_FILE"
echo "==> Video Whisper:    $VIDEO_FOR_WHISPER"
echo "==> Transcript raw:   $RAW_TRANSCRIPT_DIR"
echo "==> Transcript final: $FINAL_TXT"
echo

if [ "$NO_COMPRESS" -eq 0 ]; then
    echo "==> Comprimo il video per Whisper..."
    ffmpeg -y \
        -i "$VIDEO_FILE" \
        -vf "scale=-2:720" \
        -c:v libx264 \
        -crf 30 \
        -preset medium \
        -c:a aac \
        -b:a 80k \
        "$SMALL_VIDEO"
else
    echo "==> Salto compressione: uso direttamente il video sorgente."
fi

echo
echo "==> Attivo la venv Whisper..."
# shellcheck disable=SC1091
source "$HOME/.venvs/whisper/bin/activate"

echo
echo "==> Trascrivo con Whisper..."
whisper "$VIDEO_FOR_WHISPER" \
    --language en \
    --model base \
    --output_format txt \
    --output_dir "$RAW_TRANSCRIPT_DIR"

echo
echo "==> Copio la trascrizione finale nella cartella della lezione..."
cp "$RAW_TXT" "$FINAL_TXT"

echo
echo "==> Fatto."
echo
echo "Video usato da Whisper:"
ls -lh "$VIDEO_FOR_WHISPER"

echo
echo "Trascrizione raw:"
ls -lh "$RAW_TXT"

echo
echo "Trascrizione finale:"
ls -lh "$FINAL_TXT"

echo
echo "Anteprima trascrizione:"
echo "----------------------------------------"
sed -n '1,80p' "$FINAL_TXT"
echo "----------------------------------------"

echo
echo "File da caricare su ChatGPT:"
echo "$FINAL_TXT"
