module.exports = {
    $themeStylesheet: null,
    $dtTheme: localStorage.getItem('dt-theme'),
    $dtLayout: localStorage.getItem('dt-layout'),
    $dtStyle: localStorage.getItem('dt-style'),
    $currentTheme: this.$dtTheme ? this.$dtTheme : 'semidark',
    $currentLayout: this.$dtLayout ? this.$dtLayout : 'full-width',
    $currentThemeStyle: this.$dtStyle ? this.$dtStyle : 'style-1'
}