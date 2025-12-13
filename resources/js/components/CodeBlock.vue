<script setup lang="ts">
import { ref, computed } from 'vue'

interface Props {
    code: string
    language?: string
    filename?: string
    showLineNumbers?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    language: 'php',
    showLineNumbers: true,
})

const copied = ref(false)

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(props.code)
        copied.value = true
        setTimeout(() => {
            copied.value = false
        }, 2000)
    } catch (err) {
        console.error('Failed to copy:', err)
    }
}

// Token types for syntax highlighting
type TokenType = 'keyword' | 'variable' | 'string' | 'comment' | 'class' | 'method' | 'number' | 'operator' | 'text' | 'newline'

interface Token {
    type: TokenType
    value: string
}

// Tokenize PHP code
const tokenize = (code: string): Token[] => {
    const tokens: Token[] = []
    const keywords = new Set(['class', 'extends', 'implements', 'use', 'trait', 'interface', 'abstract', 'final', 'public', 'private', 'protected', 'static', 'function', 'return', 'new', 'if', 'else', 'elseif', 'foreach', 'for', 'while', 'switch', 'case', 'break', 'continue', 'throw', 'try', 'catch', 'finally', 'null', 'true', 'false', 'array', 'string', 'int', 'float', 'bool', 'mixed', 'void', 'self', 'parent', 'namespace', 'const', 'readonly', 'fn'])

    let i = 0
    while (i < code.length) {
        // Newlines
        if (code[i] === '\n') {
            tokens.push({ type: 'newline', value: '\n' })
            i++
            continue
        }

        // Comments
        if (code.slice(i, i + 2) === '//') {
            const end = code.indexOf('\n', i)
            const value = end === -1 ? code.slice(i) : code.slice(i, end)
            tokens.push({ type: 'comment', value })
            i += value.length
            continue
        }

        // Multi-line comments
        if (code.slice(i, i + 2) === '/*') {
            const end = code.indexOf('*/', i)
            const value = end === -1 ? code.slice(i) : code.slice(i, end + 2)
            tokens.push({ type: 'comment', value })
            i += value.length
            continue
        }

        // Strings
        if (code[i] === "'" || code[i] === '"') {
            const quote = code[i]
            let j = i + 1
            while (j < code.length && code[j] !== quote) {
                if (code[j] === '\\') j++
                j++
            }
            tokens.push({ type: 'string', value: code.slice(i, j + 1) })
            i = j + 1
            continue
        }

        // Variables
        if (code[i] === '$') {
            let j = i + 1
            while (j < code.length && /[a-zA-Z0-9_]/.test(code[j])) j++
            tokens.push({ type: 'variable', value: code.slice(i, j) })
            i = j
            continue
        }

        // Words (keywords, classes, methods)
        if (/[a-zA-Z_]/.test(code[i])) {
            let j = i
            while (j < code.length && /[a-zA-Z0-9_]/.test(code[j])) j++
            const word = code.slice(i, j)
            if (keywords.has(word)) {
                tokens.push({ type: 'keyword', value: word })
            } else if (/^[A-Z]/.test(word)) {
                tokens.push({ type: 'class', value: word })
            } else if (code[j] === '(') {
                tokens.push({ type: 'method', value: word })
            } else {
                tokens.push({ type: 'text', value: word })
            }
            i = j
            continue
        }

        // Numbers
        if (/[0-9]/.test(code[i])) {
            let j = i
            while (j < code.length && /[0-9.]/.test(code[j])) j++
            tokens.push({ type: 'number', value: code.slice(i, j) })
            i = j
            continue
        }

        // Everything else (whitespace, punctuation)
        tokens.push({ type: 'text', value: code[i] })
        i++
    }

    return tokens
}

// Get CSS class for token type
const getTokenClass = (type: TokenType): string => {
    switch (type) {
        case 'keyword': return 'text-purple-400'
        case 'variable': return 'text-cyan-400'
        case 'string': return 'text-green-400'
        case 'comment': return 'text-gray-500 italic'
        case 'class': return 'text-yellow-300'
        case 'method': return 'text-blue-400'
        case 'number': return 'text-orange-400'
        default: return 'text-gray-300'
    }
}

// Split tokens into lines for line numbering
const tokenLines = computed(() => {
    if (props.language !== 'php') {
        return props.code.split('\n').map(line => [{ type: 'text' as TokenType, value: line }])
    }

    const allTokens = tokenize(props.code)
    const lines: Token[][] = [[]]

    for (const token of allTokens) {
        if (token.type === 'newline') {
            lines.push([])
        } else {
            lines[lines.length - 1].push(token)
        }
    }

    return lines
})
</script>

<template>
    <div class="group relative rounded-xl border border-white/10 bg-gray-900/80 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-white/10 bg-gray-800/50 px-4 py-2">
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <span v-if="filename" class="ml-3 text-sm text-gray-400">{{ filename }}</span>
            </div>
            <button
                @click="copyToClipboard"
                class="flex items-center gap-1.5 rounded-lg bg-white/5 px-3 py-1.5 text-xs text-gray-400 transition hover:bg-white/10 hover:text-white"
            >
                <svg v-if="!copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ copied ? 'Copied!' : 'Copy' }}
            </button>
        </div>

        <!-- Code with syntax highlighting -->
        <div class="overflow-x-auto">
            <pre class="p-4 text-sm leading-relaxed"><code class="font-mono"><template v-for="(lineTokens, lineIndex) in tokenLines" :key="lineIndex"><span v-if="showLineNumbers" class="inline-block w-8 text-right text-gray-600 select-none mr-4">{{ lineIndex + 1 }}</span><template v-for="(token, tokenIndex) in lineTokens" :key="tokenIndex"><span :class="getTokenClass(token.type)">{{ token.value }}</span></template>
</template></code></pre>
        </div>
    </div>
</template>
