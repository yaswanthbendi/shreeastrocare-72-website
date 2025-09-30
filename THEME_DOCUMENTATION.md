# Theme Documentation - Shree Astrocare Website

## Color Palette

This website uses a production-ready theme with the following exclusive color palette:

### Primary Colors

| Color Name | Hex Code | Usage |
|------------|----------|-------|
| White | `#FFFFFF` | Backgrounds, text on dark sections, card backgrounds |
| Cream | `#F5F5DC` | Secondary backgrounds, soft accents |
| Gold | `#FFD700` | Primary CTAs, highlights, accents, underlines |
| Royal Blue | `#4169E1` | Primary headings, navigation, branding |

### Color Variations (for states and effects)

| Variant | Hex Code | Usage |
|---------|----------|-------|
| Cream Light | `#FAFAF5` | Light backgrounds |
| Cream Dark | `#E8E8CC` | Borders, subtle separators |
| Gold Light | `#FFE44D` | Hover states, gradients |
| Gold Dark | `#DAB600` | Button hover states |
| Royal Blue Light | `#5B8BF5` | Gradients, lighter accents |
| Royal Blue Dark | `#2C4BAD` | Text, dark variations |

## CSS Architecture

### Color Variables (defined in styles.css)

```css
:root {
    /* Primary Colors */
    --color-white: #FFFFFF;
    --color-cream: #F5F5DC;
    --color-gold: #FFD700;
    --color-royal-blue: #4169E1;
    
    /* Shades for states and variations */
    --color-cream-light: #FAFAF5;
    --color-cream-dark: #E8E8CC;
    --color-gold-light: #FFE44D;
    --color-gold-dark: #DAB600;
    --color-royal-blue-light: #5B8BF5;
    --color-royal-blue-dark: #2C4BAD;
}
```

## Component Color Usage

### Navigation
- Background: White to Cream gradient
- Logo: Royal Blue gradient
- Links: Royal Blue with Gold underline on hover
- Border: Gold (2px solid)

### Hero Section
- Background: Royal Blue to Royal Blue Light gradient
- Title: White
- Subtitle: Cream Light
- Primary Button: Gold gradient with Royal Blue text
- Secondary Button: White background with Royal Blue border

### About Section
- Background: Cream Light to White gradient
- Cards: White with Cream border, Gold border on hover
- Headings: Royal Blue

### Services Section
- Background: White to Cream Light gradient
- Cards: White with Gold top border
- Hover: Royal Blue top border

### Testimonials Section
- Background: Royal Blue gradient
- Cards: White with Gold left border
- Stars: Gold

### Contact Section
- Background: Cream Light to White gradient
- Form: White with Royal Blue top border
- Inputs: Cream Light background with Royal Blue focus

### Footer
- Background: Royal Blue Dark to Royal Blue gradient
- Headings: Gold
- Text: Cream Light
- Social Links: White overlay background, Gold on hover

## Accessibility

### Contrast Ratios
All color combinations meet WCAG 2.1 Level AA standards:
- Royal Blue text on Cream/White backgrounds: ✓ Passes
- White text on Royal Blue backgrounds: ✓ Passes
- Gold accents are used for decorative purposes with sufficient contrast

### Focus States
- All interactive elements have Gold outline on focus (2px solid)
- Reduced motion support included via `prefers-reduced-motion`

## Responsive Design

The theme is fully responsive across:
- Mobile devices (< 480px)
- Tablets (481px - 768px)
- Desktop (> 768px)

### Breakpoints
- `@media (max-width: 768px)` - Tablet and below
- `@media (max-width: 480px)` - Mobile devices

## Premium Design Elements

### Shadows
- Small: `0 2px 8px rgba(65, 105, 225, 0.1)` - Royal Blue tint
- Medium: `0 4px 16px rgba(65, 105, 225, 0.15)` - Royal Blue tint
- Large: `0 8px 32px rgba(65, 105, 225, 0.2)` - Royal Blue tint
- Gold: `0 4px 16px rgba(255, 215, 0, 0.3)` - For Gold buttons

### Border Radius
- Small: 4px
- Medium: 8px
- Large: 16px
- Full: 9999px (for rounded buttons)

### Gradients
All gradients use only theme colors:
- Hero: `linear-gradient(135deg, Royal Blue, Royal Blue Light)`
- Buttons: `linear-gradient(135deg, Gold, Gold Light)`
- Navigation: `linear-gradient(135deg, White, Cream)`
- Footer: `linear-gradient(135deg, Royal Blue Dark, Royal Blue)`

## File Structure

```
/
├── index.html          # Main HTML structure
├── styles.css          # All CSS with theme implementation
├── script.js           # Interactive functionality
└── THEME_DOCUMENTATION.md   # This file
```

## Browser Compatibility

Tested and compatible with:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Implementation Checklist

✅ All UI elements use only White, Cream, Gold, and Royal Blue
✅ No deprecated or old theme colors present
✅ Responsive design implemented
✅ Accessibility standards met (WCAG 2.1 AA)
✅ Premium shadows and effects applied
✅ Smooth transitions and interactions
✅ Form validation with themed messages
✅ Cross-browser compatible
✅ Mobile-friendly navigation
✅ Production-ready code

## Maintenance

When adding new components:
1. Use only the defined CSS variables
2. Maintain contrast ratios for accessibility
3. Apply consistent shadow and border-radius values
4. Test on multiple devices and browsers
5. Ensure smooth transitions (0.3s ease)

## Performance

- No external dependencies (pure HTML/CSS/JS)
- Optimized CSS with minimal redundancy
- Efficient JavaScript with event delegation
- Fast load times
- No framework overhead
