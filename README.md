# ColorBot is your personal color assistant on the web

There are plenty of tools to work with colors on the web, but I wanted to build
something like a swiss army knife for working with colors.

With ColorBot, you can:

- Convert between Hexadecimal, RGBa, and HSLa
- Create color palettes
- Easily share color palettes
- Quickly get tints and shades of a color
- And even get a rough name for a color

## Notes on development

This project was initially built for [A 10k Apart](https://a-k-apart.com/). I
built a basic PHP backend with a dash of Javascript here and there to enhance
the experience. (It should still work great whether the Javascript loads or not.)

## Built with accessibility in mind

First and foremost, I built ColorBot to be accessible. It will automatically
suggest a color name to help colorblind and blind users get a rough idea of what
color is being shown (in their palette, the selected color, and the tints and
shades). I added accesskeys, skip links, and defined focus styles for easier
keyboard navigation. And every time a new color is selected, I run a quick
calculation to decide whether to show white or black text [for the best
contrast](https://24ways.org/2010/calculating-color-contrast/).

## Looking to the future

I've really enjoyed building ColorBot and I want to continue improving it in the
future. Here are a few things I'd love to improve:

1. **Database backend**: I'd love to let people create and share multiple color
palettes, "like" others palettes, etc. This is a lot easier with a database.
2. **Reorderable palettes**: You can "hack" reordering now by changing the order
of hex codes in a shared paletted, but I'd love to add drag and drop reordering
to make it easier.
3. **Configurable thresholds**: Right now, random color generation is hard-coded
to match colors in a "flat" range (not too light, not too dark). I'd love to add
a method for letting users choose different styles of colors (earthy, greys,
pastels, all). I'd also love to add better tools for choosing custom tints and
shades.
4. **Color naming** can always be improved.