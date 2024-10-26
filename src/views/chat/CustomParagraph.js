import { Node, mergeAttributes } from '@tiptap/core'

const CustomParagraph = Node.create({
  name: 'customParagraph', // Rename to avoid conflict

  group: 'block',

  content: 'inline*',

  parseHTML() {
    return [
      { tag: 'p' },
    ]
  },

  renderHTML({ HTMLAttributes }) {
    return ['p', mergeAttributes(HTMLAttributes), 0]
  },

  addKeyboardShortcuts() {
    return {
      Enter: ({ editor }) => {
        const { state } = editor.view
        const { selection } = state
        const { empty } = selection

        // If there is a selection, prevent the default behavior
        if (!empty) {
          return true
        }

        // If no selection, insert a new paragraph
        return editor.commands.insertContent('<p></p>')
      },
    }
  },
})

export default CustomParagraph
