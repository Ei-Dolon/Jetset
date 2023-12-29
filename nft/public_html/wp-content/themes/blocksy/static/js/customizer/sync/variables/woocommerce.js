import { handleResponsiveSwitch, withKeys } from '../helpers'
import { typographyOption } from './typography'
import { handleBackgroundOptionFor } from './background'

import { getWooGeneralVariablesFor } from './woocommerce/general'
import { getWooArchiveVariablesFor } from './woocommerce/archive'
import { getWooSingleGalleryVariablesFor } from './woocommerce/single-product-gallery'
import { getWooSingleLayersVariablesFor } from './woocommerce/single-product-layers'


export const getWooVariablesFor = () => ({
	...getWooGeneralVariablesFor(),
	...getWooArchiveVariablesFor(),
	...getWooSingleGalleryVariablesFor(),
	...getWooSingleLayersVariablesFor(),
})