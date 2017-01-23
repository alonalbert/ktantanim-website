using System;
using System.Collections.Generic;
using System.Configuration;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Drawing.Imaging;
using System.Drawing.Text;
using System.IO;
using System.Security.AccessControl;
using System.Web;
using System.Web.Configuration;

/// <summary>
/// Summary description for Utils
/// </summary>
public class Utils {
    private static ImageCodecInfo jpegEncoder;
    private static ImageCodecInfo gifEncoder;

    static Utils() {
        foreach (ImageCodecInfo encoder in ImageCodecInfo.GetImageEncoders()) {
            switch (encoder.MimeType) {
                case "image/jpeg": 
                    jpegEncoder = encoder;
                    break;
                case "image/gif":
                    gifEncoder = encoder;
                    break;
            }
        }
    }

    public static string GetNewImage(HttpServerUtility server, string imagePath, string size) {
        return GetNewImage(server, imagePath, size, null, null, null, 0, FontStyle.Regular, Color.Black);
    }

    public static string GetNewImage(HttpServerUtility server, string imagePath, string text, string textKey, string fontName, float textSize, FontStyle style, Color color) {
        return GetNewImage(server, imagePath, null, text, textKey, fontName, textSize, style, color);
    }

    public static string GetNewImage(HttpServerUtility server, string imagePath, string size, string text, string textKey, string fontName, float textSize, FontStyle style, Color color) {
        // Get full path
        //
        string fullPath = server.MapPath(imagePath);

        string textPostfix = string.IsNullOrEmpty(text) ? "" : string.Format("-{0}-{1}-{2}-{3}-{4}",
                                                                             textKey ?? text,
                                                                             fontName,
                                                                             textSize,
                                                                             color.Name,
                                                                             style);
        // Generate new filename
        //
        FileInfo fileInfo = new FileInfo(fullPath);
        string extension = fileInfo.Extension;
        string directory = fileInfo.DirectoryName;
        if (!string.IsNullOrEmpty(text)) {
            directory += "/text";
        }
        string newImagePath = string.Format("{0}/{1}/{2}{3}{4}",
                                            directory,
                                            string.IsNullOrEmpty(size) ? "." : size,
                                            Path.GetFileNameWithoutExtension(imagePath),
                                            textPostfix,
                                            extension);

        newImagePath = newImagePath.Replace("\'", "");
        newImagePath = Path.GetFullPath(newImagePath).Replace('\\', '/');

        if (!File.Exists(newImagePath)) {
            // Load original image
            //
            Bitmap bitmap = (Bitmap) Image.FromFile(fullPath);
            Bitmap newBitmap = null;
            Font font = null;

            try {
                // Establish size of new image
                //
                int width;
                int height;

                if (!string.IsNullOrEmpty(size)) {
                    // If resize, calculate new size
                    //

                    // Load configyration from image folder
                    //
                    Configuration configuration = WebConfigurationManager.OpenWebConfiguration("~/" + fileInfo.DirectoryName.Substring(server.MapPath("~/").Length));

                    // Calculate new width & height
                    //
                    int x = int.Parse(configuration.AppSettings.Settings["size." + size].Value);

                    // Size from config is long dimension of image. Short dimentsion is calculated from aspect ratio
                    //
                    if (bitmap.Width > bitmap.Height) {
                        width = x;
                        height = bitmap.Height * x / bitmap.Width;
                    }
                    else {
                        width = bitmap.Width * x / bitmap.Height;
                        height = x;
                    }
                }
                else {
                    // No resize, copy size from image
                    //
                    width = bitmap.Width;
                    height = bitmap.Height;
                }

                int textHeight = 0;
                int textWidth = 0;

                // Adjust size for larger text
                //
                if (!string.IsNullOrEmpty(text)) {
                    // Create font
                    //
                    font = new Font(fontName, textSize, style);

                    // Create small bitmap for measuring text
                    //
                    Bitmap dummyBitmap = new Bitmap(1, 1);
                    Graphics textGraphics = Graphics.FromImage(dummyBitmap);

                    // Measure the text height
                    //
                    StringFormat stringformat = new StringFormat(StringFormat.GenericTypographic);
                    textHeight = Convert.ToInt32(textGraphics.MeasureString(text, font, new PointF(0, 0), stringformat).Height);
                    textWidth = Convert.ToInt32(textGraphics.MeasureString(text, font, new PointF(0, 0), stringformat).Width);
                    int marginWidth = Convert.ToInt32(textGraphics.MeasureString("XXXX", font, new PointF(0, 0), stringformat).Width);
                    dummyBitmap.Dispose();

                    // Enlarge image if text takes more space
                    //
                    if (width < textWidth + marginWidth) {
                        width = textWidth + marginWidth;
                    }

                    if (height < textHeight) {
                        height = textHeight;
                    }
                }

                // Create target bitmap
                //
                newBitmap = new Bitmap(width, height);
                
                // Add our image
                //
                Graphics newGraphics = Graphics.FromImage(newBitmap);
                newGraphics.InterpolationMode = extension == ".gif" ? InterpolationMode.NearestNeighbor : InterpolationMode.HighQualityBicubic;
                newGraphics.DrawImage(bitmap, 0, 0, width, height);

                // Draw text
                //
                if (font != null) {
                    Graphics textGraphics = Graphics.FromImage(newBitmap);
                    // Aliasing mode
                    //
                    textGraphics.TextRenderingHint = TextRenderingHint.SingleBitPerPixelGridFit;

                    // Create brush
                    //
                    SolidBrush brush = new SolidBrush(color);

                    // Draw text
                    //
                    textGraphics.DrawString(text, font, brush, new Rectangle((width - textWidth) / 2, (height - textHeight) / 2, (width + textWidth) / 2, (height + textHeight) / 2));
                }

                SaveImage(newBitmap, newImagePath);
            }
            finally {
                if (font != null) {
                    font.Dispose();
                }
                if (bitmap != null) {
                    bitmap.Dispose();
                }
                if (newBitmap != null) {
                    newBitmap.Dispose();
                }
            }
        }

        return newImagePath.Substring(server.MapPath("").Length + 1);
    }

    private static void SaveImage(Bitmap bitmap, string path) {
        DirectorySecurity directorySecurity = new DirectorySecurity();
        directorySecurity.AddAccessRule(new FileSystemAccessRule(
                                            Environment.UserDomainName + "\\" + Environment.UserName,
                                            FileSystemRights.FullControl,
                                            AccessControlType.Allow));
        Directory.CreateDirectory(Path.GetDirectoryName(path));
        
        ImageCodecInfo encoder = null;
        EncoderParameters encoderParameters = null;
        switch (Path.GetExtension(path).ToLower()) {
            case ".jpg":
                encoder = jpegEncoder;
                encoderParameters = new EncoderParameters(1);
                encoderParameters.Param[0] = new EncoderParameter(Encoder.Quality, 90L);
                bitmap.Save(path, encoder, encoderParameters);
                break;

            case ".gif": {
                Bitmap gifBitmap = CreateGifBitmap(bitmap);
                gifBitmap.Save(path.ToString(), ImageFormat.Gif);
                gifBitmap.Dispose();
                break;
            }
                
            default:
                bitmap.Save(path.ToString());
                break;
        }
    }

    private static Bitmap CreateGifBitmap(Bitmap source) {
        Bitmap dest = new Bitmap(source.Width, source.Height, PixelFormat.Format8bppIndexed);

        BitmapData bits = dest.LockBits(new Rectangle(0, 0, dest.Width, dest.Height), ImageLockMode.WriteOnly, PixelFormat.Format8bppIndexed);
        unsafe {
            byte* destPixel = (byte*) bits.Scan0.ToPointer();
            Dictionary<Color, byte> colorMap = new Dictionary<Color, byte>();
            byte paletteIndex = 0;
            ColorPalette palette = dest.Palette;
            for (int row = 0; row < source.Height; ++row) {
                for (int col = 0; col < source.Width; ++col) {
                    Color sourcePixel = source.GetPixel(col, row);
                    try {
                        *destPixel = colorMap[sourcePixel];
                    }
                    catch (KeyNotFoundException) {
                        palette.Entries[paletteIndex] = sourcePixel;
                        colorMap[sourcePixel] = paletteIndex;
                        *destPixel = paletteIndex;
                        ++paletteIndex;
                    }
                    destPixel++;
                }
                destPixel += bits.Stride - source.Width;
            }
            dest.Palette = palette;
        }
        dest.UnlockBits(bits);

        return dest;
    }
}