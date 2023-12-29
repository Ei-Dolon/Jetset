import { Fragment, createElement, Component } from '@wordpress/element'
import OptionsPanel from '../OptionsPanel'
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd'
import { reorder } from '../options/ct-layers'
import nanoid from 'nanoid'

const valueWithUniqueIds = (value) =>
	value.map((singleItem) => ({
		...singleItem,

		...(singleItem.__id
			? {}
			: {
					__id: nanoid(),
			  }),
	}))

const LayersCombined = ({ option, value, onChange }) => {
	const computedValue = Object.keys(value).reduce((acc, key) => {
		return {
			...acc,
			[key]: valueWithUniqueIds(value[key]),
		}
	}, {})

	return (
		<DragDropContext
			onDragEnd={(result) => {
				if (!result.destination) {
					return
				}

				if (
					result.destination.droppableId === result.source.droppableId
				) {
					onChange({
						...computedValue,

						[result.destination.droppableId]: reorder(
							computedValue[result.destination.droppableId],
							result.source.index,
							result.destination.index
						),
					})
					return
				}

				const current = computedValue[result.source.droppableId]
				const next = computedValue[result.destination.droppableId]
				const target = current[result.source.index]

				current.splice(result.source.index, 1)
				next.splice(result.destination.index, 0, target)

				onChange({
					...computedValue,
					[result.source.droppableId]: current,
					[result.destination.droppableId]: next,
				})
			}}>
			<OptionsPanel
				onChange={(key, optionValue) => {
					onChange({
						...computedValue,
						[key]: optionValue,
					})
				}}
				options={option['inner-options']}
				value={computedValue}
			/>
		</DragDropContext>
	)
}

export default LayersCombined
