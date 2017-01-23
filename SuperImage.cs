using System.ComponentModel;
using System.Drawing;
using System.Security.Permissions;
using System.Web;
using System.Web.UI;

namespace AutoPhoto {
  [
      AspNetHostingPermission(SecurityAction.Demand, Level = AspNetHostingPermissionLevel.Minimal),
      AspNetHostingPermission(SecurityAction.InheritanceDemand, Level = AspNetHostingPermissionLevel.Minimal),
      DefaultProperty("ImageURL"),
      ToolboxData("<{0}:SuperImage runat=\"server\"> </{0}:SuperImage>")
  ]
  public class SuperImage : System.Web.UI.WebControls.Image {
    private string imageSize = null;
    [Bindable(true), Category("Appearance"), DefaultValue(""), Description("Size of image."), Localizable(true)]
    public virtual string ImageSize {
      get { return imageSize; }
      set { imageSize = value; }
    }

    private string imageText = null;
    [Bindable(true), Category("Appearance"), DefaultValue(""), Description("Overlay text."), Localizable(true)]
    public virtual string ImageText {
      get { return imageText; }
      set { imageText = value; }
    }

    private string resource = "";
    [Bindable(true), Category("Behavior"), DefaultValue(""), Description("Navigate URL."), Localizable(true)]
    public virtual string Resource {
      get { return resource; }
      set { resource = value; }
    }

    private string imageTextKey = null;
    [Bindable(true), Category("Appearance"), DefaultValue(""), Description("Overlay text."), Localizable(true)]
    public virtual string ImageTextKey {
      get { return imageTextKey; }
      set { imageTextKey = value; }
    }

    private string textFont = "Arial";
    [Bindable(true), Category("Appearance"), DefaultValue("Arial"), Description("Overlay text font."), Localizable(true)]
    public virtual string TextFont {
      get { return textFont; }
      set { textFont = value; }
    }

    private float textSize = 10.0f;
    [Bindable(true), Category("Appearance"), DefaultValue(10), Description("Overlay text size."), Localizable(true)]
    public virtual float TextSize {
      get { return textSize; }
      set { textSize = value; }
    }

    private FontStyle textStyle = FontStyle.Regular;
    [Bindable(true), Category("Appearance"), DefaultValue("Regular"), Description("Overlay text font."), Localizable(true)]
    public virtual FontStyle TextStyle {
      get { return textStyle; }
      set { textStyle = value; }
    }

    private Color textColor = Color.Black;
    [Bindable(true), Category("Appearance"), DefaultValue("Black"), Description("Overlay text color."), Localizable(true)]
    public virtual Color TextColor {
      get { return textColor; }
      set { textColor = value; }
    }

    protected override void Render(HtmlTextWriter writer) {
      if (!string.IsNullOrEmpty(imageSize) || !string.IsNullOrEmpty(imageText) || !string.IsNullOrEmpty(resource)) {
        string t = null;
        if (!string.IsNullOrEmpty(resource)) {
          t = HttpContext.GetGlobalResourceObject("Resource", resource).ToString();
        }
        if (t == null) {
          t = imageText;
        }
        ImageUrl = Utils.GetNewImage(Page.Server, ImageUrl, imageSize, t, imageTextKey, textFont, textSize, textStyle, textColor);
      }
      base.Render(writer);
    }
  }
}