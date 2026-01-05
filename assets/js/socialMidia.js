var el = wp.element.createElement;


wp.blocks.registerBlockType('cms-adm/social-midias', {
    title: 'Icon Social', // Block name visible to user
    icon: 'share', // Toolbar icon can be either using WP Dashicons or custom SVG
    category: 'common', // Under which category the block would appear
    supports: {
        multiple: true,
    },
    attributes: { // The data this block will be storing
        youtube: { type: 'string' }, // row description
        facebook: { type: 'string' }, // row description
        twitter: { type: 'string' }, // Notice box title in h4 tag
        instagram: { type: 'string' }, // Notice box title in h4 tag
    },
    edit: function(props) {
        function updateURLFacebook(event) {
            props.setAttributes({ youtube: event.target.value });
        }

        function updateURLYoutube(event) {
            props.setAttributes({ facebook: event.target.value });
        }

        function updateURLTwitter(event) {
            props.setAttributes({ twitter: event.target.value });
        }

        function updateInstagram(event) {
            props.setAttributes({ instagram: event.target.value });
        }

        return el('div', { className: "w-100" },
            el('div', null,
                el('div', { className: "w-100" },
                    el('label', { className: "w-25 text-end p-1" }, "Facebook: "),
                    el('input', { className: "w-75", type: "text", value: props.attributes.facebook, onchange: updateURLFacebook }),
                ),
                el('div', { className: "w-100" },
                    el('label', { className: "w-25  text-end p-1" }, "Youtube: "),
                    el('input', { className: "w-75", type: "text", value: props.attributes.youtube, onchange: updateURLYoutube }),
                ),
                el('div', { className: "w-100" },
                    el('label', { className: "w-25  text-end p-1" }, "twitter: "),
                    el('input', { className: "w-75", type: "text", value: props.attributes.twitter, onchange: updateURLTwitter }),
                ),
                el('div', { className: "w-100" },
                    el('label', { className: "w-25  text-end p-1" }, "wppShare: "),
                    el('input', { className: "w-75", type: "text", value: props.attributes.instagram, onchange: updateInstagram }),
                ),
            ),
            el('div', { className: 'w-100 d-flex align-self-center my-4', },
                el('div', { className: 'separator-green ' }, ""),
                el('div', {
                        className: 'button-social-links  mx-4'
                    },
                    el('a', { className: "fcb", href: props.attributes.facebook },
                        el('i', { className: "fab fa-facebook-f " }, ),
                    ),
                    el('a', { className: "twi", href: props.attributes.twitter },
                        el('i', { className: "fab fa-twitter " }, ),
                    ),

                    el('a', { className: "you", href: props.attributes.youtube },
                        el('i', { className: "fab fa-youtube " }, ),
                    ),
                    el('a', { className: "inst", href: props.attributes.instagram },
                        el('i', { className: "fab fa-instagram " }, ),
                    ),
                    ""
                ),
                el('div', { className: 'separator-green' }, ""),
            )
        );

    },
    save: function(props) {
        return el('div', { className: 'w-100 d-flex align-self-center my-4', },
                el('div', { className: 'separator-green ' }, ""),
                el('div', {
                        className: 'button-social-links  mx-4'
                    },
                    el('a', { className: "fcb", href: props.attributes.facebook },
                        el('i', { className: "fab fa-facebook-f " }, ),
                    ),
                    el('a', { className: "twi", href: props.attributes.twitter },
                        el('i', { className: "fab fa-twitter " }, ),
                    ),

                    el('a', { className: "you", href: props.attributes.youtube },
                        el('i', { className: "fab fa-youtube " }, ),
                    ),
                    el('a', { className: "inst", href: props.attributes.instagram },
                        el('i', { className: "fab fa-instagram " }, ),
                    ),
                    ""
                ),
                el('div', { className: 'separator-green' }, ""),
            );
    }
});